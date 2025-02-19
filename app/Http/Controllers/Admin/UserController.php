<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            //code...
            $data['dashboard_title'] = "Admin Dashboard";

            $query = User::whereNull('deleted_at')->where('status', 'active');

            // Apply account goal filter
            if ($request->filled('account_goal') && $request->account_goal !== 'all') {
                $query->where('account_goal', $request->account_goal);
            }

            // Apply account type filter
            if ($request->filled('account_type') && $request->account_type !== 'all') {
                $query->where('account_type', $request->account_type);
            }

            // Apply payment setup filter
            if ($request->filled('payment_setup') && $request->payment_setup !== 'all') {
                $query->where('payment_setup', $request->payment_setup);
            }

            // Apply specific date filter
            if ($request->filled('specific_date')) {
                $query->whereDate('created_at', $request->specific_date);
            }

            // Apply date range filter
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            $data['users'] = $query->paginate(10);

            return view('admin.dashboard.users', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function destroy($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
    public function viewUser2($uuid)
    {
        try {
            //code...
            $data['dashboard_title'] = "Admin Dashboard";
            // Fetch the user by ID and ensure it is not soft-deleted
            $data['user'] = $user = User::where('uuid', $uuid)->whereNull('deleted_at')->firstOrFail();

            // $data['user'] = $user = User::where('id', $id)->whereNull('deleted_at')->first();

            if (! $user) {
                // Handle case where user is not found
                return redirect()->back()->with('error', 'User not found or has been deleted.');
            }

            // Return the user details to a Blade view
            return view('admin.dashboard.user-view', $data);
        } catch (\Throwable $th) {
            dd('');
            //throw $th;
        }
    }
    public function viewUser($uuid)
    {
        try {
            $data['dashboard_title'] = "Admin Dashboard";

            // Fetch user with related data using eager loading
            $data['user'] = $user = User::with([
                'team',                           // Primary team relationship
                'teamMemberships.team',           // Team memberships
                'address',                        // User's address
                'cards',                          // User's cards
                'wallet',                         // User's wallet
                'transactions',                   // User's transactions
                'buildCreditCards',               // Build credit cards
                'tickets',                        // User's tickets
                'address.landlordFinanceDetails', // Landlord/Finance details through address
            ])
                ->where('uuid', $uuid)
                ->whereNull('deleted_at')
                ->firstOrFail();

            if (! $user) {
                return redirect()->back()->with('error', 'User not found or has been deleted.');
            }

            // Format user metadata
            $data['metadata'] = [
                'id'               => $user->id,
                'uuid'             => $user->uuid,
                'full_name'        => $user->first_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name,
                'first_name'       => $user->first_name,
                'middle_name'      => $user->middle_name,
                'last_name'        => $user->last_name,
                'email'            => $user->email,
                'phone'            => $this->formatPhoneNumber($user->phone),
                'otp_verified'     => $user->otp_verified,
                'account_goal'     => $this->getAccountGoalDisplayName(['goal' => $user->account_goal]),
                'is_team'          => $user->is_team,
                'account_type'     => $this->formatAccountType($user->account_type),
                'payment_setup'    => $this->formatPaymentSetup($user->payment_setup, $user->account_goal),
                'status'           => $this->formatStatus($user->status),
                'created_at'       => $user->created_at->format('d M Y, h:i A'),
                'date_deactivated' => $user->date_deactivated ? $user->date_deactivated->format('d M Y, h:i A') : null,
            ];

            // Format address information if exists
            if ($user->address) {
                $data['address'] = [
                    'name'                    => $user->address->name,
                    'province'                => $user->address->province,
                    'city'                    => $user->address->city,
                    'street_name'             => $user->address->street_name,
                    'postal_code'             => $user->address->postal_code,
                    'house_number'            => $user->address->house_number,
                    'unit_number'             => $user->address->unit_number,
                    'amount'                  => $user->address->amount,
                    'reoccurring_monthly_day' => $user->address->reoccurring_monthly_day,
                    'duration_from'           => $user->address->duration_from,
                    'duration_to'             => $user->address->duration_to,
                    'tenancy_agreement'       => $user->address->tenancyAgreement,
                    'edit_count'              => $user->address->edit_count,
                    'last_edit_date'          => $user->address->last_edit_date ? $user->address->last_edit_date->format('d M Y, h:i A') : null,
                ];

                // Format address and landlord/financer details if exists
                if ($user->address->landlordFinanceDetails->isNotEmpty()) {
                    $details = $user->address->landlordFinanceDetails->first()->details;
                    // Parse the JSON details
                    $parsedDetails = json_decode($details, true);

                    $data['finance_details'] = [
                        'type'           => $user->account_goal, // 'rent' or 'mortgage'
                        'payment_method' => $user->address->landlordFinanceDetails->first()->payment_method,
                        'details'        => $parsedDetails,
                        'display_title'  => $user->account_goal === 'mortgage' ? 'Mortgage Financer' : 'Landlord',
                    ];
                }
            }

            // Format team memberships
            if ($user->team) {
                $data['team_memberships'] = $user->team->teamMembers()
                    ->whereIn('status', ['pending', 'accepted'])
                    ->get()
                    ->map(function ($member) {
                        $userData = null;
                        if ($member->status === 'accepted' && $member->user_id) {
                            $userData = User::select('first_name', 'last_name')
                                ->find($member->user_id);
                        }
            
                        return [
                            'name' => $userData 
                                ? $userData->first_name . ' ' . $userData->last_name 
                                : $member->name,
                            'email' => $member->email,
                            'role' => $member->role,
                            'status' => $member->status,
                            'amount' => $member->amount,
                            'percentage' => $member->percentage,
                            'invitation_status' => [
                                'token' => $member->invitation_token,
                                'expires_at' => $member->invitation_expires_at 
                                    ? Carbon::parse($member->invitation_expires_at)->format('d M Y, h:i A') 
                                    : null,
                                'declined_at' => $member->declined_at 
                                    ? Carbon::parse($member->declined_at)->format('d M Y, h:i A') 
                                    : null,
                            ],            
                            'is_registered' => $member->user_id ? true : false
                        ];
                    });
            }
            
            // Format credit cards information
            $data['credit_cards'] = $user->cards->map(function ($card) {
                return [
                    'is_primary'  => $card->is_primary ?? false,
                    'last_four'   => $card->last_four,
                    'card_type'   => $card->card_type,
                    'expiry_date' => $card->expiry_month . '/' . $card->expiry_year,
                    'card_limit'  => $card->card_limit ?? 0,
                    'status'      => $card->status,
                ];
            });

            // Format recent transactions
            $data['recent_transactions'] = $user->transactions()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id'             => $transaction->id,
                        'transaction_id' => $transaction->transaction_id,
                        'amount'         => $transaction->amount,
                        'card_last_four' => $transaction->card_last_four,
                        'card_type'      => $transaction->card_type,
                        'date'           => $transaction->created_at->format('d M Y, h:i A'),
                        'status'         => $transaction->status,
                    ];
                });

            $data['tickets'] = [
                'total_tickets'  => $user->tickets->count(),
                'recent_tickets' => $user->tickets()
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($ticket) {
                        return [
                            'id'         => $ticket->id,
                            'title'      => $ticket->title,
                            'status'     => $ticket->status,
                            'created_at' => $ticket->created_at->format('d M Y, h:i A'),
                        ];
                    }),
            ];

            // dd($data, $user->teamMembers, $user->team->teamMembers);

            return view('admin.dashboard.user-view', $data);

        } catch (\Exception $e) {
            dd($e);
            \Log::error('Error in viewUser: ' . $e->getMessage());
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'An error occurred while fetching user details. Please try again.');
        }
    }

    private function formatStatus($status)
    {
        $formats = [
            'active'    => ['text' => 'Active', 'class' => 'success'],
            'pending'   => ['text' => 'Pending', 'class' => 'warning'],
            'inactive'  => ['text' => 'Inactive', 'class' => 'danger'],
            'suspended' => ['text' => 'Suspended', 'class' => 'danger'],
        ];

        return $formats[$status] ?? ['text' => ucfirst($status), 'class' => 'secondary'];
    }

    // Helper method to get account goal display name
    private function getAccountGoalDisplayName($accountDetails)
    {
        if (! isset($accountDetails['goal'])) {
            return null;
        }

        // if ($accountDetails['goal'] == 'mortgage') {
        //     return $accountDetails['plan'] == 'pay_mortgage' ? 'Mortgage' : 'Mortgage and Build Credit';
        // } elseif ($accountDetails['goal'] == 'rent') {
        //     return $accountDetails['plan'] == 'pay_rent' ? 'Rent' : 'Rent and Build Credit';
        // }

        // Just return the capitalized goal if it exists
        return ucfirst($accountDetails['goal']);
        // return null;
    }

    private function formatAccountType($type)
    {
        $formats = [
            'sole_applicant' => 'Sole Applicant',
            'co_applicant'   => 'Co-applicant',
            'co_owner'       => 'Co-owner',
        ];

        return $formats[$type] ?? ucfirst($type);
    }

    private function formatPaymentSetup($setup, $account_goal)
    {
        if ($setup === 'new') {
            return $account_goal === 'rent' ? 'New Rent' : 'New Mortgage';
        } else if ($setup === 'existing') {
            return $account_goal === 'rent' ? 'Existing Rent' : 'Existing Mortgage';
        }

        return ucfirst($setup);
    }

    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return 'Not provided';
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Format as Canadian phone number: (XXX) XXX-XXXX
        if (strlen($phone) == 10) {
            return sprintf("(%s) %s-%s",
                substr($phone, 0, 3),
                substr($phone, 3, 3),
                substr($phone, 6)
            );
        }

        return $phone;
    }

    public function approveUser($uuid)
{
    try {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->status = 'active';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User has been approved successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error approving user: ' . $e->getMessage()
        ], 500);
    }
}

public function rejectUser($uuid)
{
    try {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->status = 'rejected';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User has been rejected successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error rejecting user: ' . $e->getMessage()
        ], 500);
    }
}

public function deleteUser($uuid)
{
    try {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User has been deleted successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error deleting user: ' . $e->getMessage()
        ], 500);
    }
}

}
