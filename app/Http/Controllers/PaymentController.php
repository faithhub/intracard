<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentSchedule;
use App\Models\WalletAllocation;
use App\Notifications\PaymentSuccessNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'schedule_id'    => 'required|exists:payment_schedules,id',
            'amount'         => 'required|numeric|min:0',
            'payment_method' => 'required|in:wallet,card',
            'payment_date'   => 'required|date',
            'card_id'        => 'required_if:payment_method,card|exists:user_cards,id',
            'charges'        => 'required|numeric|min:0',
            'payment_for'    => 'required|string',
            'bill_id'        => 'required|exists:bills,id',
        ]);

        try {
            $schedule = PaymentSchedule::findOrFail($request->schedule_id);

            // Start transaction
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'user_id'               => auth()->id(),
                'schedule_id'           => $schedule->id,
                'team_id'               => $schedule->team_id,
                'amount'                => $request->amount,
                'charges'               => $request->charges,
                'payment_for'           => $request->payment_for,
                'bill_id'               => $request->bill_id,
                'due_date'              => $schedule->recurring_day,
                'payment_date'          => Carbon::parse($request->payment_date),
                'payment_method'        => $request->payment_method,
                'card_id'               => $request->card_id,
                'transaction_reference' => 'TXN-' . Str::random(10),
                'status'                => 'pending',
                'is_team_payment'       => (bool) $schedule->team_id,
            ]);

            // Check wallet allocation first
            $walletAllocation = WalletAllocation::where('user_id', auth()->id())
                ->where('bill_id', $request->bill_id)
                ->where('remaining_amount', '>=', $request->amount)
                ->first();

            if ($walletAllocation) {
                // Update wallet allocation
                $walletAllocation->spent_amount += $request->amount;
                $walletAllocation->remaining_amount -= $request->amount;
                $walletAllocation->save();

                $payment->payment_method = 'wallet';
                $payment->status = 'paid';
                $payment->save();

                // Send success notification for wallet payment
                $payment->user->notify(new PaymentSuccessNotification($payment));

                // Send app notification for wallet payment
                $this->notificationService->createWalletNotification('wallet_payment_success', [
                    'amount' => $request->amount,
                    'payment_for' => $request->payment_for,
                    'payment_id' => $payment->id,
                ]);

                DB::commit();

                return response()->json([
                    'message' => 'Payment processed successfully using wallet allocation',
                    'payment' => $payment,
                ]);
            }

            // Process payment using the primary card
            $paymentSuccess = $this->processCardPayment($payment);

            if ($paymentSuccess) {
                $payment->update([
                    'status' => 'completed',
                    'notes'  => 'Payment processed successfully using card',
                ]);

                $payment->payment_method = 'card';
                $payment->status = 'paid';
                $payment->save();
                // Send success notification
                $payment->user->notify(new PaymentSuccessNotification($payment));

                // Send app notification for card payment
                $this->notificationService->createCardNotification('card_payment_success', [
                    'amount' => $request->amount,
                    'payment_for' => $request->payment_for,
                    'card_last4' => substr($payment->card->card_number, -4),
                    'payment_id' => $payment->id,
                ]);
                DB::commit();

                return response()->json([
                    'message' => 'Payment processed successfully using card',
                    'payment' => $payment,
                ]);
            }

            throw new \Exception('Payment processing failed');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Payment processing failed', [
                'error'       => $e->getMessage(),
                'user_id'     => auth()->id(),
                'schedule_id' => $request->schedule_id,
            ]);

            // Send app notification for failed wallet payment
            $this->notificationService->createWalletNotification('wallet_payment_failed', [
                'amount' => $request->amount,
                'payment_for' => $request->payment_for,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Payment processing failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function processCardPayment(Payment $payment)
    {
        // Retrieve the user's primary card
        $card = $payment->user->cards()->where('is_primary', true)->first();

        if (!$card) {
            return false;
        }

        // Implement card payment processing logic using the card details
        // ...

        return true;
    }

    // ... (getPaymentHistory and getUpcomingPayments methods remain the same)
}
