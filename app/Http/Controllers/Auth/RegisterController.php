<?php
namespace App\Http\Controllers\Auth;

use App\DataSanitizer;
// use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Mail\RegistrationVerificationMail;
use App\Mail\SendVerificationCode;
use App\Mail\TeamInvitationMail;
use App\Models\Address;
use App\Models\BuildCreditCard;
use App\Models\LandlordFinancerDetail;
use App\Models\PaymentSchedule;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use DataSanitizer;
    protected function sendOtpEmail($user, $otpCode)
    {
        Mail::send('emails.otp', ['otpCode' => $otpCode], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verify Your Account - OTP Code');
        });
    }

    public function showRegistrationForm()
    {
        // DB::table('settings')->insert([
        //     'key' => 'enable_2fa',
        //     'value' => 'false', // Default value (can be 'true' or 'false')
        //     'type' => 'boolean', // Type of the setting
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        try {
            //code...
            // Show the registration form (GET request)
            // return view('auth.reg4');
            return view('auth.register', [
                'pageTitle' => 'Intracard | Sign Up',
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            //throw $th;
        }
    }

    private function queueReminders($user, $paymentSchedule)
    {
        $reminderDates = $paymentSchedule->reminder_dates;

        // foreach ($reminderDates as $key => $date) {
        //     // Schedule an email reminder for each date
        //     Notification::route('mail', $user->email)
        //         ->later(Carbon::parse($date), new PaymentReminderNotification($paymentSchedule, $key));
        // }
        // foreach ($reminderDates as $key => $date) {
        //     Notification::route('mail', $user->email)
        //         ->later(
        //             Carbon::parse($date),
        //             (new PaymentReminderNotification($paymentSchedule, $key))->onQueue('reminders')->withTags(['payment:' . $paymentSchedule->id])
        //         );
        // }

        // foreach ($reminderDates as $key => $date) {
        //     Notification::sendLater(
        //         Carbon::parse($date),
        //         new AnonymousNotifiable,
        //         (new PaymentReminderNotification($paymentSchedule, $key))->onQueue('reminders')->withTags(['payment:' . $paymentSchedule->id]),
        //         ['mail' => $user->email]
        //     );
        // }
        foreach ($reminderDates as $key => $date) {
            Notification::send($user,
                (new PaymentReminderNotification($paymentSchedule, $key))
                    ->delay(Carbon::parse($date))
            );
        }
        // foreach ($reminderDates as $key => $date) {
        //     Notification::route('mail', $user->email)
        //         ->sendLater(
        //             Carbon::parse($date),
        //             new PaymentReminderNotification($paymentSchedule, $key)
        //         );
        // }
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

    private function sendTeamInvitationEmail($email, $name, $role, $addressName, $team, $user)
    {
        // Generate invitation token and expiry date
        $token      = Str::random(32);
        $expiryDate = Carbon::now()->addDays(7);

        // Save token to the newly created member
        TeamMember::where('email', $email)
            ->where('team_id', $team->id)
            ->where('status', 'pending')
            ->update([
                'uuid'                  => Str::uuid(), // Generate UUID if not already set
                'invitation_token'      => $token,
                'invitation_expires_at' => $expiryDate,
            ]);

        // Prepare email details
        $details = [
            'account_goal' => $user->account_goal,
            'name'         => $name,
            'admin_name'   => "{$user->first_name} {$user->last_name}",
            'team_id'      => $team->uuid,
            'address'      => $addressName,
            'role'         => $user->account_goal === 'rent' ? 'Co-Applicant' : 'Co-Owner',
            'token'        => $token,
        ];

        // Send the email
        Mail::to($email)->send(new TeamInvitationMail($details));
    }

    protected function generatePaymentSchedules2($payment_type, $recurringDay, $durationFrom, $durationTo, $biWeekly = false)
    {
        $type         = $payment_type;         // rent, mortgage, or bill
        $recurringDay = $recurringDay ?? null; // Day of the month
        $durationFrom = isset($durationFrom) ? Carbon::parse($durationFrom) : null;
        $durationTo   = isset($durationTo) ? Carbon::parse($durationTo) : null;
        $biWeekly     = $biWeekly ?? false; // Only for bills

        $schedules   = [];
        $currentDate = Carbon::now(); // Today's date

        if ($type === 'rent' || $type === 'mortgage') {
            // Generate monthly payment schedules based on duration
            $currentDate = $durationFrom->copy();
            while ($currentDate->lte($durationTo)) {
                $paymentDate = $currentDate->day($recurringDay); // Set to recurring day of the month
                if ($paymentDate->isFuture()) {
                    $schedules[] = [
                        'payment_type' => $type,
                        'payment_date' => $paymentDate->toDateString(),
                        'status'       => 'due',
                    ];
                }
                $currentDate->addMonth();
            }
        } elseif ($type === 'bill') {
            if ($biWeekly) {
                // Generate bi-weekly payment schedules (for bills)
                $currentDate = $durationFrom ? $durationFrom->copy() : $currentDate->startOfDay();
                $endDate     = $durationTo ?? $currentDate->copy()->addMonths(6); // Default range of 6 months if not provided
                while ($currentDate->lte($endDate)) {
                    if ($currentDate->isFuture()) {
                        $schedules[] = [
                            'payment_type' => 'bill',
                            'payment_date' => $currentDate->toDateString(),
                            'status'       => 'due',
                        ];
                    }
                    $currentDate->addWeeks(2);
                }
            } else {
                                                                                           // Generate monthly payment schedules (for bills)
                $startDate = $durationFrom ? $durationFrom->copy() : $currentDate->copy(); // Use today if no start date
                $endDate   = $durationTo ?? $startDate->copy()->addMonths(6);              // Default range of 6 months if not provided

                while ($startDate->lte($endDate)) {
                    $paymentDate = $startDate->day($recurringDay); // Set to recurring day of the month

                    // Skip if the recurring date is earlier than today in the first month
                    if ($paymentDate->isFuture()) {
                        $schedules[] = [
                            'payment_type' => 'bill',
                            'payment_date' => $paymentDate->toDateString(),
                            'status'       => 'due',
                        ];
                    }

                    $startDate->addMonth();
                }
            }
        }

        return $schedules;

    }

    protected function generatePaymentSchedules3($payment_type, $recurringDay, $durationFrom = null, $durationTo = null, $biWeekly = false)
    {
        try {
            //code...
            // Parse dates
            $durationFrom = $durationFrom ? Carbon::parse($durationFrom) : null;
            $durationTo   = $durationTo ? Carbon::parse($durationTo) : null;
            $currentDate  = Carbon::now(); // Today's date

            $schedules = [];

            if ($payment_type === 'rent' || $payment_type === 'mortgage') {
                                                                                 // Generate monthly payment schedules based on duration
                $startDate = $durationFrom ?: $currentDate;                      // Default to today if no start date
                $endDate   = $durationTo ?: $currentDate->copy()->addMonths(12); // Default range to 1 year if no end date

                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $paymentDate = $currentDate->copy()->day($recurringDay); // Set to recurring day of the month

                    if ($paymentDate->isFuture()) {
                        $schedules[] = [
                            'payment_type' => $payment_type,
                            'payment_date' => $paymentDate->toDateString(),
                            'status'       => 'due',
                        ];
                    }

                    $currentDate->addMonth();
                }
            } elseif ($payment_type === 'bill') {
                if ($biWeekly) {
                                                                                    // Generate bi-weekly payment schedules (for bills)
                    $startDate = $durationFrom ?: $currentDate->startOfDay();       // Start today if no start date
                    $endDate   = $durationTo ?: $currentDate->copy()->addMonths(6); // Default to 6 months if no end date

                    $currentDate = $startDate->copy();
                    while ($currentDate->lte($endDate)) {
                        if ($currentDate->isFuture()) {
                            $schedules[] = [
                                'payment_type' => 'bill',
                                'payment_date' => $currentDate->toDateString(),
                                'status'       => 'due',
                            ];
                        }
                        $currentDate->addWeeks(2);
                    }
                } else {
                                                                                  // Generate monthly payment schedules (for bills)
                    $startDate = $durationFrom ?: $currentDate->copy();           // Start today if no start date
                    $endDate   = $durationTo ?: $startDate->copy()->addMonths(6); // Default range of 6 months if no end date

                    $currentDate = $startDate->copy();
                    while ($currentDate->lte($endDate)) {
                        $paymentDate = $currentDate->copy()->day($recurringDay); // Set to recurring day of the month

                        // Skip if the recurring date is earlier than today in the first month
                        if ($paymentDate->isFuture()) {
                            $schedules[] = [
                                'payment_type' => 'bill',
                                'payment_date' => $paymentDate->toDateString(),
                                'status'       => 'due',
                            ];
                        }

                        $currentDate->addMonth();
                    }
                }
            }

            return $schedules;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function generatePaymentSchedules($payment_type, $recurringDay, $durationFrom = null, $durationTo = null, $biWeekly = false)
    {
                                                    // Validate and cast recurring day
        $recurringDay = (int) ($recurringDay ?? 1); // Default to 1 if null
        if ($recurringDay < 1 || $recurringDay > 31) {
            $recurringDay = 1; // Ensure it's within valid range
        }

        // Parse dates
        $durationFrom = $durationFrom ? Carbon::parse($durationFrom) : null;
        $durationTo   = $durationTo ? Carbon::parse($durationTo) : null;
        $currentDate  = Carbon::now(); // Today's date

        $schedules = [];

        if ($payment_type === 'rent' || $payment_type === 'mortgage') {
                                                                             // Generate monthly payment schedules
            $startDate = $durationFrom ?: $currentDate;                      // Default to today if no start date
            $endDate   = $durationTo ?: $currentDate->copy()->addMonths(12); // Default range to 1 year if no end date

            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $paymentDate = $currentDate->copy()->day($recurringDay); // Safely set the day

                if ($paymentDate->isFuture()) {
                    $schedules[] = [
                        'payment_type' => $payment_type,
                        'payment_date' => $paymentDate->toDateString(),
                        'status'       => 'due',
                    ];
                }

                $currentDate->addMonth();
            }
        } elseif ($payment_type === 'bill') {
            if ($biWeekly) {
                                                                                // Generate bi-weekly payment schedules (for bills)
                $startDate = $durationFrom ?: $currentDate->startOfDay();       // Start today if no start date
                $endDate   = $durationTo ?: $currentDate->copy()->addMonths(6); // Default to 6 months if no end date

                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    if ($currentDate->isFuture()) {
                        $schedules[] = [
                            'payment_type' => 'bill',
                            'payment_date' => $currentDate->toDateString(),
                            'status'       => 'due',
                        ];
                    }
                    $currentDate->addWeeks(2);
                }
            } else {
                                                                              // Generate monthly payment schedules (for bills)
                $startDate = $durationFrom ?: $currentDate->copy();           // Start today if no start date
                $endDate   = $durationTo ?: $startDate->copy()->addMonths(6); // Default range of 6 months if no end date

                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $paymentDate = $currentDate->copy()->day($recurringDay); // Safely set the day

                    // Skip if the recurring date is earlier than today in the first month
                    if ($paymentDate->isFuture()) {
                        $schedules[] = [
                            'payment_type' => 'bill',
                            'payment_date' => $paymentDate->toDateString(),
                            'status'       => 'due',
                        ];
                    }

                    $currentDate->addMonth();
                }
            }
        }

        return $schedules;
    }

    protected function generateUniqueTeamName()
    {
        do {
            $uniqueString = Str::random(5);
            $timestamp    = now()->format('Ymd');
            $teamName     = "Team-{$uniqueString}-{$timestamp}";
        } while (Team::where('name', $teamName)->exists());

        return $teamName;
    }

    protected function saveRelatedData(User $user, array $data)
    {
        // dd($data['accountType']['coApplicants']);
        function mapPaymentMethod($input)
        {
            $mapping = [
                'mortgage_cheque' => 'cheque',
                'EFT'             => 'EFT',
                'rentInterac'     => 'interac',
                'rentCheque'      => 'cheque',
            ];

            return $mapping[$input] ?? 'cheque'; // Default to 'cheque' if input doesn't match
        }
        // Create a wallet for the user
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0.00,                                 // Initial balance
            'details' => json_encode(['created_at' => now()]), // Optional details
        ]);

        $addressId   = null; // Initialize address ID
        $addressName = null; // Initialize address ID
        $teamId      = null; // Initialize team ID

        // Save address data if available
        if (! empty($data['addressDetails'])) {
            $address = Address::create([
                'user_id'                 => $user->id,
                'name'                    => $data['addressDetails']['address'] ?? null,
                'city'                    => $data['addressDetails']['city'] ?? null,
                'province'                => $data['addressDetails']['province'] ?? null,
                'postal_code'             => $data['addressDetails']['postalCode'] ?? null,
                'house_number'            => $data['addressDetails']['houseNumber'] ?? null,
                'unit_number'             => $data['addressDetails']['unitNumber'] ?? null,
                'street_name'             => $data['addressDetails']['streetName'] ?? null,
                'amount'                  => $data['addressDetails']['rentAmount'] ?? 0,
                'reoccurring_monthly_day' => $data['addressDetails']['reOccurringMonthlyDay'] ?? null,
                'duration_from'           => $data['addressDetails']['duration']['from'] ?? null,
                'duration_to'             => $data['addressDetails']['duration']['to'] ?? null,
                'tenancyAgreement'        => $data['addressDetails']['tenancyAgreement'] ?? null, // Assume file upload handling
            ]);

            // Store the address ID
            $addressId   = $address->id;
            $addressName = $address->name;
            function generateLandlordDetails($data)
            {
                $paymentMethod = mapPaymentMethod($data['getLandlordOrFinanceDetails']['paymentMethod']);
                $type          = $data['accountType']['goal'] ?? 'rent';
                $landlordType  = $data['getLandlordOrFinanceDetails']['landlordType'] ?? 'individual';

                // Mortgage Cheque Details
                if ($paymentMethod === 'cheque' && $type === 'mortgage') {
                    return json_encode([
                        'accountNumber'     => $data['getLandlordOrFinanceDetails']['mortgageChequeDetails']['accountNumber'] ?? null,
                        'transitNumber'     => $data['getLandlordOrFinanceDetails']['mortgageChequeDetails']['transitNumber'] ?? null,
                        'institutionNumber' => $data['getLandlordOrFinanceDetails']['mortgageChequeDetails']['institutionNumber'] ?? null,
                        'name'              => $data['getLandlordOrFinanceDetails']['mortgageChequeDetails']['name'] ?? null,
                        'address'           => $data['getLandlordOrFinanceDetails']['mortgageChequeDetails']['address'] ?? null,
                        'paymentMethod'     => $paymentMethod,
                    ]);
                }

                // Mortgage EFT Details
                if ($paymentMethod === 'EFT' && $type === 'mortgage') {
                    return json_encode([
                        'institutionNumber' => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['institutionNumber'] ?? null,
                        'transitNumber'     => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['transitNumber'] ?? null,
                        'accountNumber'     => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['accountNumber'] ?? null,
                        'bankAccountNumber' => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['bankAccountNumber'] ?? null,
                        'biWeeklyDueDate'   => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['biWeeklyDueDate'] ?? null,
                        'lenderAddress'     => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['lenderAddress'] ?? null,
                        'lenderName'        => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['lenderName'] ?? null,
                        'paymentFrequency'  => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['paymentFrequency'] ?? null,
                        'refNumber'         => $data['getLandlordOrFinanceDetails']['mortgageEftDetails']['refNumber'] ?? null,
                    ]);
                }

                // Rent Interac Details
                if ($paymentMethod === 'interac' && $type === 'rent') {
                    return json_encode([
                        'email' => $data['getLandlordOrFinanceDetails']['email'] ?? null,
                    ]);
                }

                // Rent Cheque Details for Business
                if ($paymentMethod === 'cheque' && $type === 'rent' && $landlordType === 'business') {
                    return json_encode([
                        'businessName' => $data['getLandlordOrFinanceDetails']['landlordInfo']['businessName'] ?? null,
                    ]);
                }

                // Rent Cheque Details for Individual
                if ($paymentMethod === 'cheque' && $type === 'rent' && $landlordType === 'individual') {
                    return json_encode([
                        'firstName'  => $data['getLandlordOrFinanceDetails']['landlordInfo']['firstName'] ?? null,
                        'lastName'   => $data['getLandlordOrFinanceDetails']['landlordInfo']['lastName'] ?? null,
                        'middleName' => $data['getLandlordOrFinanceDetails']['landlordInfo']['middleName'] ?? null,
                    ]);
                }
                // Default fallback
                return json_encode([]);
            }

            // Save financial details if address is created
            if ($address) {
                // dd($data['getLandlordOrFinanceDetails']['paymentMethod'], generateLandlordDetails($data));
                LandlordFinancerDetail::create([
                    'address_id'     => $address->id,
                    'type'           => $data['accountType']['goal'] ?? 'rent',                                              // Rent or mortgage
                    'payment_method' => mapPaymentMethod($data['getLandlordOrFinanceDetails']['paymentMethod'] ?? 'cheque'), // Map payment method
                    'landlord_type'  => $data['getLandlordOrFinanceDetails']['landlordType'] ?? 'individual',                // Landlord type
                    'details'        => generateLandlordDetails($data),                                                      // Generate the dynamic JSON details
                ]);
            }
        }

        // Save Team
        if (! empty($data['accountType']['coApplicants'])) {
            $team = Team::create([
                'user_id'    => $user->id,
                'address_id' => $addressId,
                'name'       => $this->generateUniqueTeamName(),
                'members'    => json_encode($data['accountType']['coApplicants']), // Store co-applicants in JSON format
            ]);

            $user->team_id = $team->id;
            $user->is_team = true;
            $user->save();

            // Add admin (current user) as a team member
            TeamMember::create([
                'team_id'    => $team->id,
                'user_id'    => $user->id,
                'name'       => "{$user->first_name} {$user->last_name}",
                'email'      => $user->email,
                'status'     => 'accepted',
                'role'       => 'admin',
                'amount'     => round(($data['accountType']['primaryRentOrMortgageAmount'] / 100) * $data['addressDetails']['rentAmount'], 2), // Admin's rent amount
                'percentage' => $data['accountType']['primaryRentOrMortgageAmount'],                                                           // Rent amount from the applicant
            ]);

            // Add co-applicants as team members
            foreach ($data['accountType']['coApplicants'] as $applicant) {
                // Determine base amount and percentage based on account type
                if ($data['accountType']['goal'] == 'rent') {
                    $baseAmount = $data['addressDetails']['rentAmount'];
                    $percentage = $applicant['rentAmount'];
                } else {
                    $baseAmount = $data['addressDetails']['rentAmount'];
                    $percentage = $applicant['mortgageAmount'];
                }

                // Calculate amount based on percentage
                $calculatedAmount = round(($percentage / 100) * $baseAmount, 2);

                $teamMemberName = `{$applicant['firstName']} {$applicant['lastName']}`;
                $memberRole     = "member";

                TeamMember::create([
                    'team_id'    => $team->id,
                    'name'       => $teamMemberName,
                    'email'      => $applicant['email'],
                    'status'     => 'pending',
                    'role'       => $memberRole,
                    'percentage' => $percentage ?? 0,
                    'amount'     => $calculatedAmount,
                ]);

                // Send email to co-applicant
                $this->sendTeamInvitationEmail($applicant['email'], $teamMemberName, $memberRole, $addressName, $team, $user);
            }
        }

        // Save credit card data if available
        if (! empty($data['accountType']['creditCardLimit']) && ! empty($data['accountType']['creditCardDueDate'])) {
            BuildCreditCard::create([
                'card_id'     => $data['card_id'] ?? null, // Pass this from the request or logic
                'user_id'     => $user->id,
                'cc_limit'    => $data['accountType']['creditCardLimit'],   // Credit card limit
                'cc_due_date' => $data['accountType']['creditCardDueDate'], // Credit card due date (day of the month)
            ]);
        }

        // Save additional related data as needed...
        // Return the address ID (null if no address was saved)
        return [
            'addressId' => $addressId,
            'teamId'    => $teamId,
        ];
    }

    public function register(Request $request)
{ // Decode JSON data

        $jsonData = json_decode($request->data, true);
        if (! $jsonData) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid JSON data',
            ], 422);
        }

        // Convert JSON data to array format
        $data = [];
        foreach ($jsonData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue)) {
                        foreach ($subValue as $thirdKey => $thirdValue) {
                            $data[$key][$subKey][$thirdKey] = $thirdValue;
                        }
                    } else {
                        $data[$key][$subKey] = $subValue;
                    }
                }
            } else {
                $data[$key] = $value;
            }
        }

        // Add tenancyAgreement file to the data structure
        if ($request->hasFile('tenancyAgreement')) {
            $data['addressDetails']['tenancyAgreement'] = $request->file('tenancyAgreement');
        }

        //  dd($request->hasFile('tenancyAgreement'));
        //  dd($data);
        $validator = Validator::make($data, [
            'personalDetails.firstName'            => 'required|string|max:50',
            'personalDetails.lastName'             => 'required|string|max:50',
            'personalDetails.middleName'           => 'nullable|string|max:50',
            'personalDetails.email'                => 'required|email|unique:users,email',
            'personalDetails.phone'                => 'required|string|max:15',
            'accountType.goal'                     => 'required|string|in:rent,mortgage',
            'accountType.applicationType'          => 'nullable|string|in:sole_applicant,co_applicant,owner,co_owner',
            'accountType.coApplicants'             => 'nullable|array',
            'accountType.coApplicants.*.firstName' => 'required_with:accountType.coApplicants|string|max:50',
            'accountType.coApplicants.*.lastName'  => 'required_with:accountType.coApplicants|string|max:50',
            'accountType.coApplicants.*.email'     => 'required_with:accountType.coApplicants|email',
            'addressDetails.tenancyAgreement'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $validator->after(function ($validator) use ($data) {
            if (! empty($data['accountType']['coApplicants'])) {
                $emails = array_column($data['accountType']['coApplicants'], 'email');
                $goal   = $data['accountType']['goal'] ?? 'rent'; // Default to 'rent' if not provided

                // Determine the message based on the account goal
                $errorMessage = $goal === 'mortgage'
                ? 'Co-owner emails must be unique.'
                : 'Co-applicant emails must be unique.';

                // Check for duplicate emails in the input
                if (count($emails) !== count(array_unique($emails))) {
                    $validator->errors()->add('accountType.coApplicants', $errorMessage);
                }

                // Check for existing emails in the team_members table
                $existingEmails = \App\Models\TeamMember::whereIn('email', $emails)->pluck('email')->toArray();

                if (! empty($existingEmails)) {
                    $existingEmailsList = implode(', ', $existingEmails);
                    $validator->errors()->add(
                        'accountType.coApplicants',
                        "The following email(s) already exist in team members: {$existingEmailsList}."
                    );
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // if (!session('veriff_verified')) {
        //     return response()->json(['message' => 'User must complete verification'], 403);
        // }

        DB::beginTransaction();
        // $jsonData = json_decode($request->data, true);

        // Sanitize all input data
        $data = $this->sanitizeData($data);

        // dd($data, $request->hasFile($data['addressDetails']['tenancyAgreement']));
        function mapPaymentSetup($input)
    {
            $mapping = [
                'Continue_paying_existing_mortgage' => 'new',
                'Continue_paying_existing_rent'     => 'new',
                'Setup_payment_new_mortgage'        => 'existing',
                'Setup_payment_new_rental'          => 'existing',
            ];

            return $mapping[$input] ?? 'new';
        }

        $paymentSetup = mapPaymentSetup($data['accountType']['paymentSetup']);

        try {

            // Handle Tenancy Agreement File Upload
            $tenancyAgreementPath = null;

            if ($request->hasFile('tenancyAgreement')) {

                // Generate a unique file name
                $uniqueName = 'TA' . strtoupper(uniqid()) . '.' . $request->file('tenancyAgreement')->getClientOriginalExtension();

                // Store the file with the custom name in the specified directory
                $tenancyAgreementPath = $request->file('tenancyAgreement')->storeAs(
                    'tenancyAgreements', // Directory
                    $uniqueName,         // Custom file name
                    'public'             // Storage disk
                );

                // Store the file path in the $data array
                $data['addressDetails']['tenancyAgreement'] = $tenancyAgreementPath;
            }

            // Now access the 'goal' field
            $user = User::create([
                'first_name'    => $data['personalDetails']['firstName'],
                'last_name'     => $data['personalDetails']['lastName'] ?? null,
                'middle_name'   => $data['personalDetails']['middleName'],
                'email'         => $data['personalDetails']['email'],
                'phone'         => $data['personalDetails']['phone'],
                'password'      => $data['personalDetails']['password'], // Hash password
                'account_goal'  => $data['accountType']['goal'],
                'account_type'  => $data['accountType']['applicationType'] ?? null,
                'payment_setup' => $paymentSetup,
                'status'        => 'pending', // Set the default status or use the input
            ]);

            // Save related data and get the address ID
            $relatedData = $this->saveRelatedData($user, $data);
            $addressId   = $relatedData['addressId'];
            $teamId      = $relatedData['teamId'];

            $recurringDay                               = $data['addressDetails']['reOccurringMonthlyDay'] ?? null;
            $data['addressDetails']['duration']['from'] = Carbon::parse($data['addressDetails']['duration']['from']) ?? null;
            $data['addressDetails']['duration']['to']   = Carbon::parse($data['addressDetails']['duration']['to']) ?? null;
            $houseAmount                                = $data['addressDetails']['rentAmount'] ?? 0;
            $paymentType                                = $data['accountType']['goal']; // rent, mortgage, or bill

            // Validate recurring day
            if (! is_numeric($recurringDay) || $recurringDay < 1 || $recurringDay > 31) {
                $recurringDay = 1; // Default to 1 if invalid
            }

            // Use both IDs for payment schedule creation
            $scheduleParams = [
                'user_id'         => $user->id,
                'payment_type'    => $paymentType,
                'recurring_day'   => (int) $recurringDay,
                'amount'          => $houseAmount,
                'address_id'      => $addressId,
                'duration_from'   => Carbon::parse($data['addressDetails']['duration']['from']) ?? null,
                'duration_to'     => Carbon::parse($data['addressDetails']['duration']['to']) ?? null,
                'status'          => 'active',
                'is_team_payment' => ! is_null($teamId),
                'team_id'         => $teamId,
            ];

            // Save the payment schedule
            $paymentSchedule = PaymentSchedule::create($scheduleParams);

            // Create reminders in the database
            $createdReminders = $paymentSchedule->createReminders();
            // \Log::info('Database Error during registration:', ['createdReminders' => $createdReminders]);

            // In your controller
            Mail::to($user->email)->send(new RegistrationVerificationMail($user));

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'User registered successfully.',
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-specific errors
            DB::rollBack();
            \Log::error('Database Error during registration:', ['error' => $e->getMessage(), 'sql' => $e->getSql()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Database error occurred during registration',
                'error'   => $e->getMessage(),
            ], 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // General exception handling (already in your code)
            DB::rollBack();
            \Log::error('Registration Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to save information',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
{
        $request->validate([
            'email'    => 'required|email',
            'otp_code' => 'required|string',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Check if the OTP is correct and not expired
        if ($user->otp_code !== $request->otp_code) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        // Mark the user as verified
        $user->is_verified    = true;
        $user->otp_code       = null; // Clear OTP
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Account verified successfully!'], 200);
    }

    public function sendEmailVerificationCode(Request $request)
{
        try {
            // Validate the email input
            $request->validate([
                'email' => 'required|email',
                'name'  => 'nullable|string',
            ]);

            // Generate a 6-digit code
            $code = rand(100000, 999999);

            // Retrieve the user's email and name from the request
            $email = $request->input('email');
            $name  = $request->input('name', 'Customer'); // Default to "Customer" if no name provided

            // Send the verification code via email
            Mail::to($email)->send(new SendVerificationCode($code, $name));

            // Store the code in the session using the email as the key
            session(["verification_code_{$email}" => $code]);

            return response()->json(['message' => 'Verification code sent successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Invalid input.',
                'errors'  => $e->errors(),
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
                'message' => 'Invalid input.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'message' => 'An error occurred during verification.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function getCities($province)
{
        // Validate the province parameter to prevent SQL injection
        if (! $province) {
            return response()->json(['error' => 'Province is required'], 400);
        }

        // Query the cities based on the selected province
        $cities = DB::table('cities')
            ->where('province_name', $province)
            ->pluck('city');

        return response()->json($cities);
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
    public function verifyPhoneCode(Request $request)
{
        try {
            // Validate phone number and code format
            $request->validate([
                'phone' => 'required|string|', // Canadian phone number with +1 code
                'code'  => 'required|digits:6',
            ]);

            $phone       = $request->input('phone');
            $enteredCode = $request->input('code');
            $storedCode  = Session::get("verification_code_{$phone}");

            if ($storedCode && $enteredCode == $storedCode) {
                // Clear the code from the session upon successful verification
                Session::forget("verification_code_{$phone}");
                return response()->json(['message' => 'Phone number verified successfully!']);
            }

            // If code doesn't match or isn't found, return an error
            return response()->json(['storedCode' => $storedCode, 'enteredCode' => $enteredCode, 'message' => 'Invalid or expired verification code.'], 422);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation error
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Throwable $th) {
            // Handle any other unexpected errors
            return response()->json([
                'message' => 'An unexpected error occurred during verification.',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function deleteUser($id)
{
        $user = User::findOrFail($id);
        $user->update(['status' => 'deleted']);

        return response()->json([
            'success' => true,
            'message' => 'User marked as deleted.',
        ]);
    }

    public function restoreUser($id)
{
        $user = User::where('id', $id)->where('status', 'deleted')->firstOrFail();
        $user->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'User restored successfully.',
        ]);
    }

    public function getUsersByStatus($status)
{
        $users = User::where('status', $status)->get();

        return response()->json($users);
    }

}
