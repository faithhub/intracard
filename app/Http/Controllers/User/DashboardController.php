<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\SendVerificationCode;
use App\Models\Address;
use App\Models\Member;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            //code...
            $data['dashboard_title'] = "My Dashboard";
            return view('user.index', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    private function getPaymentSetupMessage($user)
    {
        $hasBuildCreditCards = $user->buildCreditCards()->exists();

        if ($hasBuildCreditCards) {
            return $user->account_goal === 'mortgage'
            ? 'Paying for Mortgage and Build Credit Card'
            : 'Paying for Rent and Build Credit Card';
        }

        return $user->account_goal === 'mortgage'
        ? 'Paying for Mortgage Only'
        : 'Paying for Rent only';
    }

    private function formatAccountType($accountType)
    {
        $types = [
            'co_owner'       => 'Co-Owner',
            'owner'          => 'Owner',
            'sole_applicant' => 'Sole Applicant',
            'co_applicant'   => 'Co-Applicant',
        ];

        return $types[$accountType] ?? $accountType;
    }

    public function user_details(Request $request)
    {
        try {
            $user         = $request->user();
            $response     = [];
            $account_goal = $user->account_goal;

            // dd($user);
            // Get team address if user is a team member
            $address = null;
            if ($user->is_team) {
                $teamMember = TeamMember::where([
                    'user_id' => $user->id,
                    'status'  => 'accepted',
                ])
                    ->with('team')
                    ->first();

                if ($teamMember && $teamMember->team->address_id) {
                    $address = Address::find($teamMember->team->address_id);
                }
            } else {
                $address = $user->address;
            }

            if ($request->has('personal_info')) {
                $response['personal_info'] = [
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'email'         => $user->email,
                    'phone'         => $user->phone,
                    'account_goal'  => $user->account_goal,
                    'account_type'  => $this->formatAccountType($user->account_type),
                    'payment_setup' => $user->payment_setup,
                    'payment_plan'  => $this->getPaymentSetupMessage($user),
                    // 'buildCreditCards' => $user->buildCreditCards,
                ];
            }

            if ($request->has('transactions')) {
                $response['transactions'] = $user->transactions()->latest()->limit(10)->get();
            }

            // Rest of your code remains the same, just update the address section
            // if ($request->has('address')) {
            //     $response['address'] = $address ? [
            //         ...$address->toArray(),
            //         'title' => $user->account_goal === 'mortgage'
            //             ? 'Mortgage Finance Details'
            //             : 'Landlord Details',
            //     ] : null;
            // }

            // if ($request->has('address')) {
            //     if ($user->is_team) {
            //         // Get team member details for percentage calculation
            //         // Get team with its address and members
            //         $team = Team::where('id', $user->team_id)
            //             ->with(['address', 'teamMembers' => function ($query) use ($user) {
            //                 $query->where('user_id', $user->id)
            //                     ->where('status', 'accepted');
            //             }])
            //             ->first();

            //         // dd($team, $user);

            //         if ($team && $team->address) {
            //             $teamMember = $team->teamMembers->first(); // This will get the current user's team membership
            //             if ($teamMember) {
            //                 $totalAmount      = $team->address->amount;
            //                 $percentage       = $teamMember->percentage;
            //                 $calculatedAmount = ($totalAmount * ($percentage / 100));

            //                 $response['address'] = [
            //                      ...$team->address->toArray(),
            //                     'title'             => $user->account_goal === 'mortgage'
            //                     ? 'Mortgage Finance Details'
            //                     : 'Landlord Details',
            //                     'account_goal'      => $user->account_goal,
            //                     'total_amount'      => $totalAmount,
            //                     'team_percentage'   => $percentage,
            //                     'calculated_amount' => $calculatedAmount,
            //                     'amount'            => $calculatedAmount,
            //                     'is_team_member'    => true,
            //                 ];
            //             }
            //         }
            //     } else {
            //         $response['address'] = $user->address ? [
            //              ...$user->address->toArray(),
            //             'title'          => $user->account_goal === 'mortgage'
            //             ? 'Mortgage Finance Details'
            //             : 'Landlord Details',
            //             'is_team_member' => false,
            //             'total_amount'   => $user->address->amount,
            //             'account_goal'   => $user->account_goal,
            //         ] : null;
            //     }
            // }

            if ($request->has('address')) {
                if ($user->is_team) {
                    // Get team member details for percentage calculation
                    // Get team with its address and members
                    $team = Team::where('id', $user->team_id)
                        ->with(['address', 'teamMembers' => function ($query) use ($user) {
                            $query->where('user_id', $user->id)
                                ->where('status', 'accepted');
                        }])
                        ->first();
            
            
                    if ($team && $team->address) {
                        $teamMember = $team->teamMembers->first(); // This will get the current user's team membership
                        if ($teamMember) {
                            $totalAmount      = $team->address->amount;
                            $percentage       = $teamMember->percentage;
                            $calculatedAmount = ($totalAmount * ($percentage / 100));
            
                            $response['address'] = [
                                ...$team->address->toArray(),
                                'title'             => $user->account_goal === 'mortgage'
                                    ? 'Mortgage Finance Details'
                                    : 'Landlord Details',
                                'account_goal'      => $user->account_goal,
                                'total_amount'      => $totalAmount,
                                'team_percentage'   => $percentage,
                                'calculated_amount' => $calculatedAmount,
                                'amount'            => $calculatedAmount,
                                'is_team_member'    => true,
                                // Add this to correctly fetch the tenancy agreement
                                'tenancy_agreement_url' => $team->address->tenancyAgreement 
                                    ? asset('storage/' . $team->address->tenancyAgreement) 
                                    : null,
                            ];
                        }
                    }
                } else {
                    $response['address'] = $user->address ? [
                        ...$user->address->toArray(),
                        'title'          => $user->account_goal === 'mortgage'
                            ? 'Mortgage Finance Details'
                            : 'Landlord Details',
                        'is_team_member' => false,
                        'total_amount'   => $user->address->amount,
                        'account_goal'   => $user->account_goal,
                        // Add this to correctly fetch the tenancy agreement
                        'tenancy_agreement_url' => $user->address->tenancyAgreement 
                            ? asset('storage/' . $user->address->tenancyAgreement) 
                            : null,
                    ] : null;
                }
            }

            // Update landlord_finance section to use the correct address
            if ($request->has('landlord_finance')) {
                if ($account_goal === 'rent') {
                    $response['landlord'] = $address
                    ? $address->landlordFinanceDetails()->get()
                    : null;
                }
                if ($account_goal === 'mortgage') {
                    $response['financer'] = $address
                    ? $address->landlordFinanceDetails()->first()
                    : null;
                }

                $response['landlord_finance'] = $address
                ? $address->landlordFinanceDetails()->get()
                : null;
            }

            if ($request->has('wallet')) {
                $response['wallet'] = $user->wallet;
            }

            if ($request->has('cards')) {
                $response['cards'] = $user->cards;
            }

            if ($request->has('build_credit')) {
                $response['build_credit'] = $user->buildCreditCards()->first();
            }

            if ($request->has('team')) {
                $team = Team::with(['teamMembers' => function ($query) {
                    $query->where('status', '!=', 'deactivated');
                }])
                    ->where('id', $user->team_id)
                    ->first();

                if ($team) {
                    $response['teamData'] = $team;
                }
            }
            if ($request->has('co_applicant')) {
                $team = Team::where('user_id', $user->id)->first();
                if ($team) {
                    $response['co_applicant'] = $team->teamMembers()
                        ->where('status', '!=', 'deactivated')
                        ->get();
                }
            }
            // Rest of your existing code...

            return response()->json($response);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function mortgage()
    {
        try {
            //code...
            $data['dashboard_title'] = "Mortgage Details";
            return view('user.mortgage', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function rental()
    {
        try {
            //code...
            $data['dashboard_title'] = "Rental Details";
            return view('user.rent', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function profile()
    {
        try {
            //code...
            $data['dashboard_title'] = "Rental Details";
            $data['team']            = $team            = Team::where('admin_id', Auth::user()->id)->first();
            $data['members']         = Member::where('team_id', $team->id)->get();
            return view('user.profile', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function password()
    {
        try {
            //code...
            $data['dashboard_title'] = "Rental Details";
            return view('user.password', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function advisory()
    {
        try {
            //code...
            $data['dashboard_title'] = "Credit Advisory";
            return view('user.advisory', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function getCalendarEvents()
    {
        // Simulated address details from the database or another source
        $addressDetails = json_decode(Auth::user()->address_details, true);
        $account_type   = Auth::user()->account_type; // Get account type dynamically

        // Extract duration details
        $startDate = Carbon::parse($addressDetails['duration']['from']);
        $endDate   = Carbon::parse($addressDetails['duration']['to']);

        // Generate events based on rent or mortgage duration
        $events = [];

        // Add last payment as an event
        $events[] = [
            'title'       => 'Last Payment',
            'start'       => '2024-08-15',
            'description' => 'Last payment of $718.00, status: Paid',
            'amount'      => 718.00,    // Optional: Include payment amount
            'status'      => 'Paid',    // Optional: Include status
            'color'       => '#133f1a', // Yellow
        ];

        // Add upcoming payment as an event
        $events[] = [
            'title'       => 'Upcoming Payment Due',
            'start'       => '2024-12-15',
            'description' => 'Upcoming payment of $700.00, status: Pending',
            'amount'      => 700.00,    // Optional: Include payment amount
            'status'      => 'Pending', // Optional: Include status
            'color'       => '#40C057', // Yellow
        ];

        while ($startDate <= $endDate) {
            // Determine whether to use "rent" or "mortgage"
            $paymentType       = $account_type === 'rent' ? 'Rent' : 'Mortgage';
            $descriptionPrefix = $account_type === 'rent' ? 'Monthly rent payment for ' : 'Monthly mortgage payment for ';

            $events[] = [
                'title'       => "{$paymentType} Payment Due", // Rent Payment Due or Mortgage Payment Due
                'start'       => $startDate->format('Y-m-d'),
                'end'         => $startDate->copy()->addDay()->format('Y-m-d'), // Optional: Add one day for an "end" time
                'description' => $descriptionPrefix . $addressDetails['address'],
                'amount'      => $addressDetails['rentAmount'], // Optional: Include rent amount if needed
                'color'       => '#FFC53D',                     // Yellow
            ];

            // Move to the next month
            $startDate->addMonth();
        }

        // Return events as JSON
        return response()->json($events);
    }

    public function checkEmailExists(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $emailExists = User::where('email', $request->email)->exists();

        if ($emailExists) {
            return response()->json(['status' => 'error', 'message' => 'Email already exists.'], 409); // 409 Conflict
        }

        return response()->json(['status' => 'success', 'message' => 'Email is available.'], 200);
    }

    public function sendPhoneVerificationCode(Request $request)
    {
        try {
            // Validate phone number format
            $request->validate([
                'phone' => 'required|string|', // Canadian phone number with +1 code
            ]);

            // Generate a 6-digit verification code
            $code  = rand(100000, 999999);
            $phone = $request->input('phone');

            // Store the code in the session using the phone number as the key
            Session::put("verification_code_{$phone}", $code);

            // Send the code via Twilio
            // $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            // $twilio->messages->create($phone, [
            //     'from' => env('TWILIO_PHONE_NUMBER'),
            //     'body' => "Your CrediPay verification code is: $code",
            // ]);

            return response()->json(['message' => 'Verification code sent successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Invalid phone number format.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Twilio\Exceptions\RestException $e) {
            // Handle Twilio API errors
            return response()->json([
                'message' => 'Failed to send SMS via Twilio.',
                'error'   => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            // Handle any other errors
            return response()->json([
                'message' => 'An unexpected error occurred while sending the verification code.',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function sendEmailCode(Request $request)
    {
        try {
            // Validate the email input
            // $request->validate([
            //     'email' => 'required|email',
            // ]);

            $request->validate([
                'email' => [
                    'required',
                    'email',
                    // Conditionally apply the `Rule::unique` only for 'new' type
                    $request->input('type') === 'new'
                    ? Rule::unique('users', 'email')
                    : 'sometimes',
                ],
                'type'  => ['required', 'in:current,new'], // Ensure type is either 'current' or 'new'
            ]);

            // Generate a 6-digit code
            $code = rand(100000, 999999);

            // Retrieve the user's email and name from the request
            $email = $request->input('email');
            $name  = Auth::user()->first_name ?? "Customer"; // Default to "Customer" if no name provided

            // Send the verification code via email
            Mail::to($email)->send(new SendVerificationCode($code, $name));

            // Store the code in the session using the email as the key
            session(["verification_code_{$email}" => $code]);

            return response()->json(['message' => 'Verification code sent successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Validation failed.',
                'input'   => $request->all(),
                'errors'  => $e->errors(), // Detailed validation errors
            ], 422);
        } catch (\Exception $e) {
            // Handle email sending error or other exceptions
            return response()->json([
                'message' => 'Failed to send verification code.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function verifyEmailCode(Request $request)
    {
        try {
            // Validate the email and code input
            $request->validate([
                'email' => 'required|email',
                'code'  => 'required|digits:6',
            ]);

            $email       = $request->input('email');
            $enteredCode = $request->input('code');

            // Retrieve the code from the session using the email key
            $storedCode = session("verification_code_{$email}");

            if ($storedCode && $enteredCode == $storedCode) {
                // Clear the session code after successful verification
                session()->forget("verification_code_{$email}");
                return response()->json(['message' => 'Code validated successfully!']);
            }

            // If the code is invalid or expired
            return response()->json(['message' => 'Invalid or expired code.'], 422);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $e->errors(), // Detailed validation errors
            ], 422);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'message' => 'An error occurred during verification.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function verifyEmailCodeAndUpdateEmail(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'current_email' => 'required|email',                    // Validate the current email
                'new_email'     => 'required|email|unique:users,email', // Validate the new email
            ]);

            // Get the authenticated user
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not authenticated.',
                ], 401);
            }

            // Validate the current email matches the user's existing email
            if ($user->email !== $validatedData['current_email']) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'The current email does not match our records.',
                ], 403);
            }

            // Update the user's email to the new email
            $user->email = $validatedData['new_email'];
            $user->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Your email has been updated successfully.',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation error
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $e->errors(), // Detailed validation errors
            ], 422);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'message' => 'An error occurred during verification.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
