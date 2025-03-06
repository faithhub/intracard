<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Bill;
use App\Models\Card;
use App\Models\User;
use App\Models\VeriffSession;
use App\Models\Wallet;
use App\Models\WalletAllocation;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

            // First check for any Veriff session by email, regardless of status
            $veriffSession = VeriffSession::where('email', $user->email)
                ->orderBy('created_at', 'desc')
                ->first();

// Initialize verification data in metadata
            $data['metadata']['verification'] = [
                'has_session'   => false,
                'is_approved'   => false,
                'status'        => 'pending',
                'basic_info'    => null,
                'detailed_info' => null,
            ];

            if ($veriffSession) {
// We have at least a session, populate basic info
                $data['metadata']['verification']['has_session'] = true;
                $data['metadata']['verification']['basic_info']  = [
                    'session_id'  => $veriffSession->session_id,
                    'created_at'  => $veriffSession->created_at->format('F j, Y'),
                    'vendor_data' => $veriffSession->vendor_data,
                    'end_user_id' => $veriffSession->end_user_id,
                ];

// Check if status is approved
                if ($veriffSession->status === 'approved' && ! empty($veriffSession->webhook_payload)) {
                    $data['metadata']['verification']['is_approved'] = true;
                    $data['metadata']['verification']['status']      = 'approved';

                    // Extract detailed verification info from webhook payload
                    $payload = $veriffSession->webhook_payload;

                    // Initialize detailed info object
                    $detailedInfo = [];

                    // Extract data if verification structure exists
                    // Process webhook payload if available

                    if (!empty($veriffSession->webhook_payload)) {
                        $payload = $veriffSession->webhook_payload;

                        // If it's still a JSON string, decode it
                        if (is_string($payload)) {
                            $payload = json_decode($payload, true);
                        }

                        // Basic verification info
                         if (is_array($payload) && isset($payload['verification'])) {
                            $v = $payload['verification']; // Shorthand for verification object

                            // Basic verification metadata
                            $detailedInfo['attempt_id']    = $v['attemptId'] ?? $v['id'] ?? null;
                            $detailedInfo['status']        = $v['status'] ?? 'pending';
                            $detailedInfo['code']          = $v['code'] ?? null;
                            $detailedInfo['reason']        = $v['reason'] ?? null;
                            $detailedInfo['reason_code']   = $v['reasonCode'] ?? null;
                            $detailedInfo['decision_time'] = isset($v['decisionTime']) ?
                            Carbon::parse($v['decisionTime'])->format('F j, Y') : null;
                            $detailedInfo['acceptance_time'] = isset($v['acceptanceTime']) ?
                            Carbon::parse($v['acceptanceTime'])->format('F j, Y') : null;
                            $detailedInfo['vendor_data'] = $v['vendorData'] ?? null;
                            $detailedInfo['end_user_id'] = $v['endUserId'] ?? null;

                            // User defined status based on verification status
                            if ($detailedInfo['status'] === 'approved') {
                                $detailedInfo['user_defined_status'] = 'Verified';
                            } elseif ($detailedInfo['status'] === 'declined') {
                                $detailedInfo['user_defined_status'] = 'Rejected';
                            } elseif ($detailedInfo['status'] === 'resubmission_requested') {
                                $detailedInfo['user_defined_status'] = 'Resubmission Needed';
                            }

                            // Person information
                            if (isset($v['person'])) {
                                $p                           = $v['person']; // Shorthand for person object
                                $detailedInfo['person'] = [
                                    'first_name'     => $p['firstName'] ?? null,
                                    'last_name'      => $p['lastName'] ?? null,
                                    'full_name'      => $p['fullName'] ?? null,
                                    'date_of_birth'  => $p['dateOfBirth'] ?? null,
                                    'year_of_birth'  => $p['yearOfBirth'] ?? null,
                                    'place_of_birth' => $p['placeOfBirth'] ?? null,
                                    'gender'         => $p['gender'] ?? null,
                                    'nationality'    => $p['nationality'] ?? null,
                                    'citizenship'    => $p['citizenship'] ?? null,
                                    'id_number'      => $p['idNumber'] ?? null,
                                    'occupation'     => $p['occupation'] ?? null,
                                    'employer'       => $p['employer'] ?? null,
                                    'title'          => $p['title'] ?? null,
                                ];

                                // Address information
                                if (isset($p['addresses']) && ! empty($p['addresses'])) {
                                    $address                                = $p['addresses'][0];
                                    $detailedInfo['person']['address'] = [
                                        'full_address' => $address['fullAddress'] ?? null,
                                    ];

                                    // Parse address components if available
                                    if (isset($address['parsedAddress'])) {
                                        $pa                                                     = $address['parsedAddress'];
                                        $detailedInfo['person']['address']['city']         = $pa['city'] ?? null;
                                        $detailedInfo['person']['address']['state']        = $pa['state'] ?? null;
                                        $detailedInfo['person']['address']['street']       = $pa['street'] ?? null;
                                        $detailedInfo['person']['address']['country']      = $pa['country'] ?? null;
                                        $detailedInfo['person']['address']['postcode']     = $pa['postcode'] ?? null;
                                        $detailedInfo['person']['address']['house_number'] = $pa['houseNumber'] ?? null;
                                        $detailedInfo['person']['address']['unit']         = $pa['unit'] ?? null;
                                    }
                                }
                            }

                            // Document information
                            if (isset($v['document'])) {
                                $d                             = $v['document']; // Shorthand for document object
                                $detailedInfo['document'] = [
                                    'number'         => $d['number'] ?? null,
                                    'type'           => $d['type'] ?? null,
                                    'type_display'   => $this->formatDocumentType($d['type'] ?? null),
                                    'country'        => $d['country'] ?? null,
                                    'state'          => $d['state'] ?? null,
                                    'valid_from'     => $d['validFrom'] ?? null,
                                    'valid_until'    => $d['validUntil'] ?? null,
                                    'place_of_issue' => $d['placeOfIssue'] ?? null,
                                    'first_issue'    => $d['firstIssue'] ?? null,
                                    'issue_number'   => $d['issueNumber'] ?? null,
                                    'issued_by'      => $d['issuedBy'] ?? null,
                                    'nfc_validated'  => $d['nfcValidated'] ?? null,
                                ];
                            }

                            // Risk score information
                            if (isset($v['riskScore'])) {
                                $detailedInfo['risk_score'] = $v['riskScore']['score'] ?? null;
                            }

                            // Additional verified data (pick relevant fields)
                            if (isset($v['additionalVerifiedData'])) {
                                $avd                                  = $v['additionalVerifiedData']; // Shorthand
                                $detailedInfo['additional_data'] = [
                                    'estimated_age'          => $avd['estimatedAge'] ?? null,
                                    'drivers_license_number' => $avd['driversLicenseNumber'] ?? null,
                                ];

                                // Add categories if available
                                if (isset($avd['driversLicenseCategories'])) {
                                    $detailedInfo['additional_data']['license_categories'] =
                                        implode(', ', $avd['driversLicenseCategories']);
                                }
                            }
                        }
                    }
                    $data['metadata']['verification']['detailed_info'] = $detailedInfo;
                } else {
                    // Session exists but not approved yet
                    $data['metadata']['verification']['status'] = $veriffSession->status ?? 'pending';
                }
            }

            // Format address information if exists
            // Determine which address to use based on team status
            $address = null;
            if ($user->is_team && $user->team && $user->team->address_id) {
                // Get address from the team if user is part of a team
                $address = Address::find($user->team->address_id);
            } else {
                // Fallback to user's direct address
                $address = $user->address;
            }

// Format address information if exists
            if ($address) {
                $tenancyAgreementUrl = null;
                if ($address->tenancyAgreement) {
                    $tenancyAgreementUrl = asset('storage/' . $address->tenancyAgreement);
                }

                $data['address'] = [
                    'name'                    => $address->name,
                    'province'                => $address->province,
                    'city'                    => $address->city,
                    'street_name'             => $address->street_name,
                    'postal_code'             => $address->postal_code,
                    'house_number'            => $address->house_number,
                    'unit_number'             => $address->unit_number,
                    'amount'                  => $address->amount,
                    'reoccurring_monthly_day' => $address->reoccurring_monthly_day,
                    'duration_from'           => $address->duration_from,
                    'duration_to'             => $address->duration_to,
                    'tenancy_agreement'       => $tenancyAgreementUrl, // Full URL to the file
                    'edit_count'              => $address->edit_count,
                    'last_edit_date'          => $address->last_edit_date ? $address->last_edit_date->format('d M Y, h:i A') : null,
                ];

                // Format address and landlord/financer details if exists
                if ($address->landlordFinanceDetails->isNotEmpty()) {
                    $details = $address->landlordFinanceDetails->first()->details;
                    // Parse the JSON details
                    $parsedDetails = json_decode($details, true);

                    $data['finance_details'] = [
                        'type'           => $user->account_goal, // 'rent' or 'mortgage'
                        'payment_method' => $address->landlordFinanceDetails->first()->payment_method,
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
                            'name'              => $userData
                            ? $userData->first_name . ' ' . $userData->last_name
                            : $member->name,
                            'email'             => $member->email,
                            'role'              => $member->role,
                            'status'            => $member->status,
                            'amount'            => $member->amount,
                            'percentage'        => $member->percentage,
                            'invitation_status' => [
                                'token'       => $member->invitation_token,
                                'expires_at'  => $member->invitation_expires_at
                                ? Carbon::parse($member->invitation_expires_at)->format('d M Y, h:i A')
                                : null,
                                'declined_at' => $member->declined_at
                                ? Carbon::parse($member->declined_at)->format('d M Y, h:i A')
                                : null,
                            ],
                            'is_registered'     => $member->user_id ? true : false,
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

            // Format wallet information
            if ($user->wallet) {
                $data['wallet'] = [
                    'balance' => $user->wallet->balance ?? 0,
                    'uuid'    => $user->wallet->uuid,
                ];

                // Get recent wallet transactions
                $data['wallet_transactions'] = $user->wallet->transactions()
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function ($transaction) {
                        // Get the user for the transaction to display decrypted names
                        $transactionUser = User::find($transaction->user_id);
                        $userName        = $transactionUser ?
                        $transactionUser->first_name . ' ' . $transactionUser->last_name :
                        'Unknown User';

                        return [
                            'id'        => $transaction->id,
                            'uuid'      => $transaction->uuid,
                            'type'      => $transaction->type,
                            'amount'    => $transaction->amount,
                            'charge'    => $transaction->charge,
                            'status'    => $transaction->status,
                            'date'      => $transaction->created_at->format('d M Y, h:i A'),
                            'user_name' => $userName,
                            'details'   => json_decode($transaction->details), // If details are stored as JSON
                        ];
                    });

                // Get wallet allocations if they exist
                if (method_exists($user->wallet, 'allocations')) {
                    $data['wallet_allocations'] = $user->wallet->allocations()
                        ->with('bill') // Eager load bill relationship
                        ->get()
                        ->map(function ($allocation) {
                            return [
                                'uuid'             => $allocation->uuid,
                                'allocated_amount' => $allocation->allocated_amount,
                                'spent_amount'     => $allocation->spent_amount,
                                'remaining_amount' => $allocation->remaining_amount,
                                'bill'             => $allocation->bill,
                            ];
                        });
                }
            }

            $data['tickets'] = [
                'total_tickets'  => $user->tickets->count(),
                'recent_tickets' => $user->tickets()
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($ticket) {
                        return [
                            'id'          => $ticket->id,
                            'uuid'        => $ticket->uuid,
                            'subject'     => $ticket->subject,
                            'description' => $ticket->description,
                            'status'      => $ticket->status,
                            'created_at'  => $ticket->created_at->format('d M Y, h:i A'),
                        ];
                    }),
            ];

            // dd($data, $user->teamMembers, $user->team->teamMembers);
            // dd($data);

            return view('admin.dashboard.user-view', $data);

        } catch (\Exception $e) {
            dd($e);
            \Log::error('Error in viewUser: ' . $e->getMessage());
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'An error occurred while fetching user details. Please try again.');
        }
    }

    private function formatDocumentType($docType)
    {
        if (empty($docType)) {
            return 'Unknown Document';
        }

        $types = [
            'DRIVERS_LICENSE'  => "Driver's License",
            'ID_CARD'          => "Government ID Card",
            'PASSPORT'         => "Passport",
            'RESIDENCE_PERMIT' => "Residence Permit",
            'VISA'             => "Visa",
        ];

        return $types[$docType] ?? ucwords(strtolower(str_replace('_', ' ', $docType)));
    }

    public function getWalletHistory($uuid)
    {
        try {
            // Check if it's an AJAX request
            if (! request()->ajax()) {
                return redirect()->route('admin.dashboard');
            }
            $wallet = Wallet::where('uuid', $uuid)->firstOrFail();

            // Get all transactions for this wallet with pagination
            $transactions = $wallet->transactions()
                ->with('user') // Eager load user relationship
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($transaction) {
                    // Parse details to get card_id and service_id (bill_id)
                    $details = json_decode($transaction->details, true) ?: [];

                    // Get card details if card_id exists
                    $card = null;
                    if (isset($details['card_id'])) {
                        $card = Card::find($details['card_id']);
                    }

                    // Get bill/service details if allocation_id or service_id exists
                    $bill = null;
                    if (isset($details['allocation_id'])) {
                        $allocation = WalletAllocation::find($details['allocation_id']);
                        if ($allocation && $allocation->bill_id) {
                            $bill = Bill::find($allocation->bill_id);
                        }
                    } elseif (isset($details['service_id'])) {
                        $bill = Bill::find($details['service_id']);
                    }

                    // Format the transaction data
                    return [
                        'id'        => $transaction->id,
                        'uuid'      => $transaction->uuid,
                        'type'      => $transaction->type,
                        'amount'    => $transaction->amount,
                        'charge'    => $transaction->charge,
                        'status'    => $transaction->status,
                        'date'      => $transaction->created_at->format('d M Y, h:i A'),
                        'user_name' => $transaction->user ?
                        $transaction->user->first_name . ' ' . $transaction->user->last_name :
                        'Unknown User',
                        'details'   => $details,
                        'card'      => $card ? [
                            'id'         => $card->id,
                            'number'     => $card->card_number,
                            'type'       => $card->type,
                            'expiry'     => $card->expiry_month . '/' . $card->expiry_year,
                            'is_primary' => $card->is_primary,
                        ] : null,
                        'bill'      => $bill ? [
                            'id'     => $bill->id,
                            'name'   => $bill->name,
                            'value'  => $bill->value,
                            'status' => $bill->status,
                        ] : null,
                    ];
                });

            // dd($transactions);

            // Return a partial view with just the transaction table
            return view('admin.dashboard.view-wallet', [
                'transactions' => $transactions,
                'wallet'       => $wallet,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            $user         = User::where('uuid', $uuid)->firstOrFail();
            $user->status = 'active';
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User has been approved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function rejectUser($uuid)
    {
        try {
            $user         = User::where('uuid', $uuid)->firstOrFail();
            $user->status = 'rejected';
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User has been rejected successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting user: ' . $e->getMessage(),
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
                'message' => 'User has been deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage(),
            ], 500);
        }
    }

}
