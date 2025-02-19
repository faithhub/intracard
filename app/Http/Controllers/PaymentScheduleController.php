<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillHistory;
use App\Models\PaymentSchedule;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

class PaymentScheduleController extends Controller
{

    public function indexOld()
    {
        $user = auth()->user();

        // If user is part of a team (either as member or admin)
        if ($user->is_team) {
            // Check if user is a team member
            $teamMember = TeamMember::where([
                'user_id' => $user->id,
                'status'  => 'accepted',
            ])
                ->with('team.creator')
                ->first();

            // Check if user is a team admin
            $teamAdmin = Team::where('user_id', $user->id)->first();

            // Determine user's role and percentage
            $userRole    = $teamMember ? 'member' : 'admin';
            $percentage  = $teamMember->percentage;
            $teamId      = $teamMember ? $teamMember->team_id : ($teamAdmin ? $teamAdmin->id : null);
            $userIdToUse = $teamMember ? $teamMember->team->user_id : $user->id;

            // Get the correct user ID for fetching schedules
            $userIdToUse = $teamMember ? $teamMember->team->user_id : $user->id;
        } else {
            // Regular user not in any team
            $userIdToUse = $user->id;
            $userRole    = 'regular';
            $percentage  = 100;
            $teamId      = null;
        }

        $paymentSchedules = PaymentSchedule::where('user_id', $userIdToUse)
            ->select('id', 'payment_type', 'frequency', 'recurring_day', 'duration_from', 'duration_to', 'status', 'amount', 'bill_history_id', 'reminder_dates')
            ->get()
            ->map(function ($schedule) use ($user, $userRole, $percentage, $teamId) {
                $recurringDates = $schedule->generateRecurringDates();
                return collect($recurringDates)->map(function ($date) use ($schedule, $user, $userRole, $percentage, $teamId) {
                    $totalAmount = $schedule->amount;

                    // dd( $schedule);
                    // Calculate amount based on percentage
                    $amount = $totalAmount * ($percentage / 100);

                    return [
                        'id'             => $schedule->id,
                        'payment_type'   => $schedule->payment_type,
                        'payment_date'   => $date,
                        'reminder_dates' => $schedule->generateRemindersForDate($date),
                        'payment_dates'  => json_decode($schedule->reminder_dates, true),
                        'status'         => $schedule->status,
                        'amount'         => $amount,
                        'total_amount'   => $totalAmount,
                        'bill_details'   => $billDetails ?? null,
                        'bill_type'      => $billType ?? null,
                        'team_info'      => $teamId ? [
                            'team_id'    => $teamId,
                            'role'       => $userRole,
                            'percentage' => $percentage,
                        ] : null,
                    ];
                });
            })->flatten(1);

        return response()->json($paymentSchedules);
    }

    public function index()
    {
        $user = auth()->user();
        // $PS = PaymentSchedule::where('user_id', $user->id)->get();
        // // dd($PS);
        
        // return response()->json($PS);

        if ($user->is_team) {
            $teamMember = TeamMember::where([
                'user_id' => $user->id,
                'status'  => 'accepted',
            ])
                ->with('team.creator')
                ->first();

            $teamAdmin  = Team::where('user_id', $user->id)->first();
            $userRole   = $teamMember ? 'member' : 'admin';
            $percentage = $teamMember ? $teamMember->percentage : 100;
            $teamId     = $teamMember ? $teamMember->team_id : ($teamAdmin ? $teamAdmin->id : null);
            $addressId  = $teamMember ? $teamMember->team->address_id : ($teamAdmin ? $teamAdmin->address_id : null);

            $schedules = PaymentSchedule::where(function ($query) use ($addressId, $user) {
                $query->where(function ($q) use ($addressId) {
                    $q->where('address_id', $addressId)
                        ->whereIn('payment_type', ['rent', 'mortgage']);
                })
                    ->orWhere(function ($q) use ($user) {
                        $q->where('user_id', $user->id)
                            ->where('payment_type', 'bill');
                    });
            });
        } else {
            $schedules  = PaymentSchedule::where('user_id', $user->id);
            $userRole   = 'regular';
            $percentage = 100;
            $teamId     = null;
        }

        // dd($schedules->get(), $user->id, $user, $teamAdmin);
        $paymentSchedules = $schedules
            ->with(['address', 'billHistory.bill']) // Eager load relationships
            ->select('id', 'payment_type', 'frequency', 'recurring_day', 'duration_from', 'duration_to', 'status', 'address_id', 'bill_history_id', 'reminder_dates')
            ->get()
            ->map(function ($schedule) use ($user, $userRole, $percentage, $teamId) {
                $recurringDates = $schedule->generateRecurringDates();

                return collect($recurringDates)->map(function ($date) use ($schedule, $user, $userRole, $percentage, $teamId) {
                    // Get amount based on payment type
                    if ($schedule->payment_type === 'bill') {
                        // Skip if there's no billHistory for a bill payment type
                        if (!$schedule->billHistory) {
                            return null; // This will be filtered out later
                        }
                        $totalAmount = $schedule->billHistory->amount ?? 0;
                        $amount = $totalAmount; // Bills don't use percentage split
                    } else {
                        $totalAmount = $schedule->address->amount ?? 0;
                        $amount      = $totalAmount * ($percentage / 100); // Apply percentage for rent/mortgage
                    }

                    return [
                        'id'             => $schedule->id,
                        'payment_type'   => $schedule->payment_type,
                        'payment_date'   => $date,
                        'reminder_dates' => $schedule->generateRemindersForDate($date),
                        'payment_dates'  => json_decode($schedule->reminder_dates, true),
                        'status'         => $schedule->status,
                        'amount'         => $amount,
                        'total_amount'   => $totalAmount,
                        // 'bill_details'   =>  $schedule->payment_type === 'bill' ? $schedule->billHistory : null,
                        // 'bill_type' => $schedule->payment_type === 'bill' ? ($schedule->billHistory->bill->name ?? null) : null,
                        // 'bill_details' => $schedule->payment_type === 'bill' ? $schedule->billHistory : null,
                        'bill_type'      => $schedule->payment_type === 'bill' ? ($schedule->billHistory->bill->name ?? null) : null,
                        'bill_details'   => $schedule->payment_type === 'bill' ? [
                            'name'           => $schedule->billHistory?->bill?->name,
                            'provider'       => $schedule->billHistory?->provider,
                            'account_number' => $schedule->billHistory?->account_number,
                            'due_date'       => $schedule->billHistory?->due_date,
                            'phone'          => $schedule->billHistory?->phone,
                            'frequency'      => $schedule->billHistory?->frequency,
                            'amount'         => $schedule->billHistory?->amount,
                            'status'         => $schedule->billHistory?->status,
                            // Add null check for car details
                            'car_details'    => $schedule->billHistory?->bill?->value === 'carBill' ? [
                                'car_model' => $schedule->billHistory?->car_model,
                                'car_year'  => $schedule->billHistory?->car_year,
                                'car_vin'   => $schedule->billHistory?->car_vin,
                            ] : null,
                        ] : null,
                        // 'bill_details' => $schedule->payment_type === 'bill' ? [
                        //     'provider' => $schedule->billHistory->provider ?? null,
                        //     'account_number' => $schedule->billHistory->account_number ?? null,
                        //     'due_date' => $schedule->billHistory->due_date ?? null,
                        //     // Add other bill details as needed
                        // ] : null,
                        'team_info'      => $teamId ? [
                            'team_id'    => $teamId,
                            'role'       => $userRole,
                            'percentage' => $percentage,
                        ] : null,
                    ];
                });
            // })->flatten(1)->filter();
        })->flatten(1)
        ->filter()
        ->values(); // Add values() to reset array keys to sequential numbers
            // })->flatten(1);

        return response()->json($paymentSchedules);
    }

    public function cancelReminders($paymentScheduleId)
    {
        // Find the payment schedule to ensure it exists
        $paymentSchedule = PaymentSchedule::findOrFail($paymentScheduleId);

        // Cancel the batch of jobs associated with this payment schedule
        Bus::batch()->cancel(['payment:' . $paymentScheduleId]);

        return response()->json(['message' => 'Reminders canceled for payment schedule ID: ' . $paymentScheduleId]);
    }

    public function markAsPaid($paymentScheduleId)
    {
        $paymentSchedule = PaymentSchedule::findOrFail($paymentScheduleId);

        // Update the payment status
        $paymentSchedule->status = 'paid';
        $paymentSchedule->save();

        // Cancel reminders for this payment
        $this->cancelReminders($paymentScheduleId);

        return response()->json(['message' => 'Payment marked as paid and reminders canceled.']);
    }

}
