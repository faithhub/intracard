<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\DeactivationCodeMail;
use App\Mail\DeactivationConfirmationMail;
use App\Mail\PasswordUpdated;
use App\Models\Address;
use App\Models\AddressEditLog;
use App\Models\LandlordFinancerDetail;
use App\Models\PaymentSchedule;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index(Request $request)
    {
        $page         = $request->input('page', 1);
        $itemsPerPage = $request->input('itemsPerPage', 5);
        $sortBy       = $request->input('sortBy', 'id');
        $sortDesc     = $request->input('sortDesc', false) ? 'desc' : 'asc';

        $query     = TeamMember::orderBy($sortBy, $sortDesc);
        $paginated = $query->paginate($itemsPerPage);

        return response()->json($paginated);
    }
    public function show(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name,
            'middle_name' => $user->middle_name,
            'phone'       => $user->phone,
            'email'       => $user->email,
        ]);
    }

    public function sendVerificationCode(Request $request)
    {
        $user = Auth::user();

        // Generate a 6-digit OTP code
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the OTP code and set expiration
        $user->update([
            'otp_code'       => Hash::make($otpCode),
            'otp_expires_at' => Carbon::now()->addMinutes(15),
            'otp_verified'   => false,
        ]);

        // Send verification email
        Mail::send('emails.verification-code', ['code' => $otpCode], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Profile Update Verification Code');
        });

        return response()->json(['message' => 'Verification code sent successfully']);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'middle_name'      => 'nullable|string|max:255',
            'phone'            => 'required|string|regex:/^\d{10}$/',
            'email'            => 'required|email|max:255|unique:users,email,' . $user->id,
            'verificationCode' => 'required_if:email,' . $user->email,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if email is being changed
        $isEmailChange = $request->email !== $user->email;

        if ($isEmailChange) {
            // Verify the OTP if email is being changed
            if (! $user->otp_code ||
                ! $request->verificationCode ||
                ! Hash::check($request->verificationCode, $user->otp_code) ||
                Carbon::parse($user->otp_expires_at)->isPast()) {
                return response()->json([
                    'message' => 'Invalid or expired verification code',
                ], 422);
            }
        }

        try {
            // Update user profile
            $user->update([
                'first_name'     => $request->first_name,
                'last_name'      => $request->last_name,
                'middle_name'    => $request->middle_name,
                'phone'          => $request->phone,
                'email'          => $request->email,
                // Reset OTP fields if email was changed
                'otp_code'       => $isEmailChange ? null : $user->otp_code,
                'otp_expires_at' => $isEmailChange ? null : $user->otp_expires_at,
                'otp_verified'   => $isEmailChange ? false : $user->otp_verified,
            ]);

            // If email was changed, send confirmation email
            if ($isEmailChange) {
                Mail::send('emails.email-updated', [], function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Email Address Updated Successfully');
                });
            }

            return response()->json([
                'message' => 'Profile updated successfully',
                'user'    => $user,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update profile',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        // Get the authenticated user
        // $user = auth()->user();

        $user = User::find(Auth::user()->id);
        // $user->makeVisible('password');
        // dd([
        //     'input_password' => $request->current_password,
        //     'hashed_password' => $user->password,
        //     'request' => $request->all(),
        //     'hash_check_result1' => Hash::check('Oluwadara+1', $user->password),
        //     'hash_check_result' => Hash::check($request->new_password, $user->password),
        // ]);

        // Check if the current password matches the hashed password in the database
        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.',
                'user'    => $user,
            ], 422);
        }

        // Update the password
        $user->password = $request->new_password;
        $user->save();

        // Send email notification using the mailable class
        Mail::to($user->email)->send(new PasswordUpdated($user));

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    public function deactivateAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = auth()->user();

        // Verify the code
        if ($user->otp_code !== $request->code || now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired deactivation code.'], 422);
        }

        // Deactivate the account
        $user->status         = 'deactivated';
        $user->otp_code       = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Account deactivated successfully.']);
    }

    public function sendDeactivationCode(Request $request)
    {
        $user = auth()->user();
        $code = rand(100000, 999999); // Generate a 6-digit random code

        // Save the code and expiration to the database
        $user->otp_code       = $code;
        $user->otp_expires_at = now()->addMinutes(15); // Code expires in 15 minutes
        $user->save();

        // Send the email with the deactivation code
        Mail::to($user->email)->send(new DeactivationCodeMail($code));

        return response()->json(['message' => 'Deactivation code sent successfully.']);
    }

    public function sendDeactivationEmail(Request $request)
    {
        $user = auth()->user();
        Mail::to($user->email)->send(new DeactivationConfirmationMail($user));

        return response()->json(['message' => 'Deactivation email sent successfully.']);
    }

    // AddressController.php
    public function updateAddress(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();

            // Get the address based on user's status (team member or individual)
            $address = null;
            if ($user->team_id) {

                // First check if user is admin
                $isAdmin = TeamMember::where('team_id', Auth::user()->team_id)
                    ->where('user_id', Auth::id())
                    ->where('role', 'admin')
                    ->where('status', '!=', 'deactivated')
                    ->exists();

                if (! $isAdmin) {
                    return response()->json(['message' => 'Unauthorized. Only team admins can perform this action.'], 403);
                }

                // User is part of a team
                if (! $user->team->address) {
                    throw new ModelNotFoundException('No address found for this team');
                }
                $address = Address::where('uuid', $user->team->address->uuid)
                    ->lockForUpdate() // Prevent race conditions
                    ->firstOrFail();

                                                          // Check if user has permission to edit team address
                if ($user->team->user_id !== $user->id) { // using user_id for creator check
                    return response()->json([
                        'message' => 'Only team creator can update the address',
                    ], 403);
                }
            } else {
                // User is individual
                $address = Address::where('user_id', $user->id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            // dd($address->landlordFinanceDetails(), $request->all());
            // Validation rules
            $validated = $request->validate([
                'address'          => 'required|string|max:255',
                'province'         => 'required|string|max:255',
                'city'             => 'required|string|max:255',
                'postalCode'       => ['required', 'regex:/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i'],
                'houseNumber'      => 'required|string|max:50',
                'streetName'       => 'required|string|max:255',
                'monthlyAmount'    => 'required|numeric|min:100|max:500000',
                'paymentDay'       => 'required|integer|min:1|max:31',
                'startDate'        => 'required|date',
                'endDate'          => 'required|date|after:startDate',
                'tenancyAgreement' => 'sometimes|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
                'paymentMethod'    => 'required|string|in:interac,cheque,eft',
                'payment_details'  => 'required|json',
            ]);

            // $validated['startDate'] = "2025-01-13";
            // Parse dates using the correct format
            $startDate = Carbon::createFromFormat('Y-m-d', $validated['startDate'])->startOfDay();
            $endDate   = Carbon::createFromFormat('Y-m-d', $validated['endDate'])->endOfDay();

            // Check edit limit with proper indexing
            $editCount = AddressEditLog::where('user_id', auth()->id())
                ->whereYear('edited_at', now()->year)
                ->select('id')
                ->count();

            if ($editCount >= 8) {
                DB::rollback();
                return response()->json([
                    'message' => 'You have reached the maximum number of edits for this year',
                ], 403);
            }

            // Handle file upload
            $tenancyAgreementPath = $this->handleFileUpload($request, $address);

            // Update address with sanitized data
            $address->update([
                'name'                    => strip_tags($validated['address']),
                'amount'                  => $validated['monthlyAmount'],
                'province'                => strip_tags($validated['province']),
                'city'                    => strip_tags($validated['city']),
                'street_name'             => strip_tags($validated['streetName']),
                'postal_code'             => strip_tags($validated['postalCode']),
                'house_number'            => strip_tags($validated['houseNumber']),
                'unit_number'             => strip_tags($request->unitNumber ?? ''),
                'reoccurring_monthly_day' => $validated['paymentDay'],
                'duration_from'           => $validated['startDate'],
                'duration_to'             => $validated['endDate'],
                'tenancyAgreement'        => $tenancyAgreementPath,
            ]);

            // Update or create landlord finance details
            $paymentDetails = json_decode($validated['payment_details'], true);

            $landlordFinanceData = [
                'payment_method' => $validated['paymentMethod'],
                'type'           => $user->account_goal, // 'rent' or 'mortgage'
                'details'        => json_encode($paymentDetails),
            ];

            if ($user->account_goal === 'rent') {
                $landlordFinanceData['landlordType'] = $paymentDetails['landlordType'] ?? null;
            }

            // Update or create the landlord finance record
            $address->landlordFinanceDetails()->updateOrCreate(
                ['address_id' => $address->id],
                $landlordFinanceData
            );

            AddressEditLog::create([
                'address_id' => $address->id,
                'user_id'    => auth()->id(),
                'edited_at'  => now(),
            ]);

            // Update payment schedule
            $paymentSchedule = PaymentSchedule::where('address_id', $address->id)
                ->where('user_id', $user->id)
                ->first();

            if ($paymentSchedule) {
// Check if any schedule-related fields have changed
                $scheduleChanged = false;
                $timingChanged   = false;
                $changedFields   = [];

// Check timing-related changes
                if ($paymentSchedule->recurring_day != (int) $validated['paymentDay']) {
                    $scheduleChanged = true;
                    $timingChanged   = true;
                    $changedFields[] = 'recurring day';
                }
                if ($paymentSchedule->duration_from->format('Y-m-d') !== $startDate->format('Y-m-d')) {
                    $scheduleChanged = true;
                    $timingChanged   = true;
                    $changedFields[] = 'start date';
                }
                if ($paymentSchedule->duration_to->format('Y-m-d') !== $endDate->format('Y-m-d')) {
                    $scheduleChanged = true;
                    $timingChanged   = true;
                    $changedFields[] = 'end date';
                }

// Check non-timing changes
                if ($paymentSchedule->amount != $validated['monthlyAmount']) {
                    $scheduleChanged = true;
                    $changedFields[] = 'monthly amount';
                }

// Only update if there are changes
                if ($scheduleChanged) {
                    $updateData = [
                        'payment_type'  => $user->account_goal,
                        'recurring_day' => (int) $validated['paymentDay'],
                        'amount'        => $validated['monthlyAmount'],
                        'duration_from' => $startDate,
                        'duration_to'   => $endDate,
                        'status'        => 'active',
                    ];

                    // Only reset and regenerate reminders if timing changed
                    if ($timingChanged) {
                        $updateData['reminder_dates'] = null;
                        $paymentSchedule->update($updateData);

                        // Generate new reminders
                        $reminders = $paymentSchedule->generateReminders();
                        $paymentSchedule->update(['reminder_dates' => json_encode($reminders)]);
                    } else {
                        // Update without touching reminders
                        $paymentSchedule->update($updateData);
                    }

                    // Create notification with specific changes
                    $changedFieldsText = implode(', ', $changedFields);
                    $this->notificationService->create(
                        'Payment Schedule Updated',
                        "Your payment schedule has been updated (Changed: {$changedFieldsText}). Next payment is scheduled for " .
                        $startDate->format('F j, Y'),
                        'payment',
                        [
                            'address_id'          => $address->id,
                            'payment_schedule_id' => $paymentSchedule->id,
                        ],
                        $user->id
                    );
                }
            } else {
// Create new payment schedule if none exists
                $paymentSchedule = PaymentSchedule::create([
                    'user_id'        => $user->id,
                    'payment_type'   => $user->account_goal,
                    'recurring_day'  => (int) $validated['paymentDay'],
                    'amount'         => $validated['monthlyAmount'],
                    'address_id'     => $address->id,
                    'duration_from'  => $startDate,
                    'duration_to'    => $endDate,
                    'reminder_dates' => null,
                    'status'         => 'active',
                ]);

// Generate reminders for new schedule
                $reminders = $paymentSchedule->generateReminders();
                $paymentSchedule->update(['reminder_dates' => json_encode($reminders)]);
            }

            // After the payment schedule update/creation, before DB::commit()

// Create appropriate notification based on user type
            if ($user->team_id) {
                // Team address update notification
                $this->notificationService->create(
                    'Team Address Updated',
                    'The team address has been updated successfully. Next payment is scheduled for ' .
                    Carbon::parse($validated['startDate'])->format('F j, Y'),
                    'payment',
                    [
                        'address_id'          => $address->id,
                        'payment_schedule_id' => $paymentSchedule->id,
                    ],
                    $user->id
                );

                // Notify other team members
                $teamMembers = TeamMember::where('team_id', $user->team_id)
                    ->where('user_id', '!=', $user->id)
                    ->where('status', '=', 'accepted')
                    ->get();

                foreach ($teamMembers as $member) {
                    $this->notificationService->create(
                        'Team Address Updated',
                        'Your team admin has updated the address details. Next payment is scheduled for ' .
                        Carbon::parse($validated['startDate'])->format('F j, Y'),
                        'payment',
                        [
                            'address_id'          => $address->id,
                            'payment_schedule_id' => $paymentSchedule->id,
                        ],
                        $member->user_id
                    );
                }
            } else {
                // Individual user address update notification
                $this->notificationService->create(
                    'Address Updated',
                    'Your address details have been updated successfully. Next payment is scheduled for ' .
                    Carbon::parse($validated['startDate'])->format('F j, Y'),
                    'payment',
                    [
                        'address_id'          => $address->id,
                        'payment_schedule_id' => $paymentSchedule->id,
                    ],
                    $user->id
                );
            }

            DB::commit();

            return response()->json(['message' => 'Address updated successfully', 'address' => $address]);

        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Address not found or you do not have permission to update it',
            ], 404);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Address update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while updating the address',
            ], 500);
        }
    }

    public function updateAddress2(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();

            // Get the address based on user's status (team member or individual)
            $address = null;
            if ($user->team_id) {
                if (! $user->team->address) {
                    throw new ModelNotFoundException('No address found for this team');
                }
                $address = Address::where('uuid', $user->team->address->uuid)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Check if user has permission to edit team address
                if ($user->team->user_id !== $user->id) {
                    return response()->json([
                        'message' => 'Only team creator can update the address',
                    ], 403);
                }
            } else {
                $address = Address::where('user_id', $user->id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            // ... [Previous validation code remains the same] ...

            // Parse dates using the correct format
            $startDate = Carbon::createFromFormat('Y-m-d', $validated['startDate'])->startOfDay();
            $endDate   = Carbon::createFromFormat('Y-m-d', $validated['endDate'])->endOfDay();

            // Update the address
            // ... [Previous address update code remains the same] ...

            // Update payment schedule
            $paymentSchedule = PaymentSchedule::where('address_id', $address->id)
                ->where('user_id', $user->id)
                ->first();

            if ($paymentSchedule) {
                // Update existing payment schedule
                $paymentSchedule->update([
                    'payment_type'   => $user->account_goal,
                    'recurring_day'  => (int) $validated['paymentDay'],
                    'amount'         => $validated['monthlyAmount'],
                    'duration_from'  => $startDate,
                    'duration_to'    => $endDate,
                    'reminder_dates' => null, // Reset reminders as we'll regenerate them
                    'status'         => 'active',
                ]);

                // Generate new reminders
                $reminders = $paymentSchedule->generateReminders();
                $paymentSchedule->update(['reminder_dates' => json_encode($reminders)]);

                // Create notification for updated schedule
                $this->notificationService->create(
                    'Payment Schedule Updated',
                    'Your payment schedule has been updated. Next payment is scheduled for ' .
                    $startDate->format('F j, Y'),
                    'payment',
                    [
                        'address_id'          => $address->id,
                        'payment_schedule_id' => $paymentSchedule->id,
                    ],
                    $user->id
                );
            } else {
                // Create new payment schedule if none exists
                $paymentSchedule = PaymentSchedule::create([
                    'user_id'        => $user->id,
                    'payment_type'   => $user->account_goal,
                    'recurring_day'  => (int) $validated['paymentDay'],
                    'amount'         => $validated['monthlyAmount'],
                    'address_id'     => $address->id,
                    'duration_from'  => $startDate,
                    'duration_to'    => $endDate,
                    'reminder_dates' => null,
                    'status'         => 'active',
                ]);

                // Generate reminders for new schedule
                $reminders = $paymentSchedule->generateReminders();
                $paymentSchedule->update(['reminder_dates' => json_encode($reminders)]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Address and payment schedule updated successfully',
                'data'    => [
                    'address'          => $address->fresh()->load('landlordFinanceDetails'),
                    'payment_schedule' => $paymentSchedule->fresh(),
                ],
            ]);

        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Address not found or you do not have permission to update it',
            ], 404);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Address update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while updating the address',
            ], 500);
        }
    }

    public function setupAddress(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();

            // Check if user is eligible to setup address
            if ($user->team_id) {
                return response()->json([
                    'message' => 'You are already part of a team',
                ], 422);
            }
            if ($user->address) {
                return response()->json([
                    'message' => 'You already have an active address',
                ], 422);
            }

            // dd($request->all());
            // Validation rules
            $validated = $request->validate([
                'address'          => 'required|string|max:255',
                'province'         => 'required|string|max:255',
                'city'             => 'required|string|max:255',
                'postalCode'       => ['required', 'regex:/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i'],
                'houseNumber'      => 'required|string|max:50',
                'streetName'       => 'required|string|max:255',
                'monthlyAmount'    => 'required|numeric|min:100|max:500000',
                'paymentDay'       => 'required|integer|min:1|max:31',
                // 'startDate'        => 'required|date',
                // 'endDate'          => 'required|date|after:startDate',
                'startDate'        => [
                    'required',
                    'date_format:Y-m-d',
                    function ($attribute, $value, $fail) {
                        try {
                            $date                   = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
                            $firstDayOfCurrentMonth = Carbon::today()->startOfMonth();

                            // Check if date is before the first day of current month
                            if ($date->lt($firstDayOfCurrentMonth)) {
                                $fail('Start date cannot be before the current month.');
                            }

                            if ($date->gt(Carbon::today()->addYears(2))) {
                                $fail('Start date cannot be more than 2 years in the future.');
                            }
                        } catch (\Exception $e) {
                            $fail('Invalid date format for start date.');
                        }
                    },
                ],
                'endDate'          => [
                    'required',
                    'date_format:Y-m-d',
                    function ($attribute, $value, $fail) use ($request) {
                        try {
                            $endDate   = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
                            $startDate = Carbon::createFromFormat('Y-m-d', $request->startDate)->startOfDay();

                            if ($endDate->lte($startDate)) {
                                $fail('End date must be after start date.');
                            }

                            // if ($endDate->diffInMonths($startDate) < 1) {
                            //     $fail('Duration must be at least 1 month.');
                            // }

                            // if ($endDate->diffInYears($startDate) > 5) {
                            //     $fail('Duration cannot exceed 5 years.');
                            // }
                        } catch (\Exception $e) {
                            $fail('Invalid date format for end date.');
                        }
                    },
                ],
                'tenancyAgreement' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
                'paymentMethod'    => 'required|string|in:interac,cheque,eft',
                'payment_details'  => 'required|json',
            ]);

            // Parse dates with fallbacks
            // Parse dates using the correct format
            $startDate = Carbon::createFromFormat('Y-m-d', $validated['startDate'])->startOfDay();
            $endDate   = Carbon::createFromFormat('Y-m-d', $validated['endDate'])->endOfDay();

            // $startDate = Carbon::parse($validated['startDate']) ?? null;
            // $endDate   = Carbon::parse($validated['endDate']) ?? null;

            // Validate dates are not null
            if (! $startDate || ! $endDate) {
                throw ValidationException::withMessages([
                    'dates' => 'Valid start and end dates are required',
                ]);
            }

            // Validate and set recurring day
            $recurringDay = $validated['paymentDay'];
            if (! is_numeric($recurringDay) || $recurringDay < 1 || $recurringDay > 31) {
                $recurringDay = 1; // Default to 1st of the month if invalid
            }

            // dd($validated);
            // Handle Tenancy Agreement File Upload
            $tenancyAgreementPath = null;
            if ($request->hasFile('tenancyAgreement')) {
                $uniqueName = 'TA' . strtoupper(uniqid()) . '.' .
                $request->file('tenancyAgreement')->getClientOriginalExtension();

                $tenancyAgreementPath = $request->file('tenancyAgreement')->storeAs(
                    'tenancyAgreements',
                    $uniqueName,
                    'public'
                );
            }

            // Create Address with parsed dates
            $address = Address::create([
                'user_id'                 => auth()->id(),
                'name'                    => strip_tags($validated['address']),
                'amount'                  => $validated['monthlyAmount'],
                'province'                => strip_tags($validated['province']),
                'city'                    => strip_tags($validated['city']),
                'street_name'             => strip_tags($validated['streetName']),
                'postal_code'             => strip_tags($validated['postalCode']),
                'house_number'            => strip_tags($validated['houseNumber']),
                'unit_number'             => strip_tags($request->unitNumber ?? ''),
                'reoccurring_monthly_day' => $recurringDay,
                'duration_from'           => $startDate,
                'duration_to'             => $endDate,
                'tenancyAgreement'        => $tenancyAgreementPath,
            ]);

            // Create landlord finance details
            $paymentDetails = json_decode($validated['payment_details'], true);

            $landlordFinanceData = [
                'address_id'     => $address->id,
                'payment_method' => $validated['paymentMethod'],
                'type'           => $user->account_goal,
                'details'        => json_encode($paymentDetails),
            ];

            // Set landlordType only for rent scenarios where payment method is cheque
            if ($user->account_goal === 'rent' && $validated['paymentMethod'] === 'cheque') {
                $landlordFinanceData['landlordType'] = $paymentDetails['landlordType'];
            }

            LandlordFinancerDetail::create($landlordFinanceData);

            // Create Payment Schedule with same parsed dates
            $paymentSchedule = PaymentSchedule::create([
                'user_id'        => $user->id,
                'payment_type'   => $user->account_goal,
                'recurring_day'  => (int) $recurringDay,
                'amount'         => $validated['monthlyAmount'],
                'address_id'     => $address->id,
                'duration_from'  => $startDate,
                'duration_to'    => $endDate,
                'reminder_dates' => null,
                'status'         => 'active',
            ]);

            // Generate reminders using the model method
            $reminders = $paymentSchedule->generateReminders();
            $paymentSchedule->update(['reminder_dates' => json_encode($reminders)]);

            // Create notification using the service
            $this->notificationService->create(
                'Address Setup Complete',
                'Your address has been successfully set up. Your first payment is scheduled for ' .
                Carbon::parse($validated['startDate'])->format('F j, Y'),
                'payment',
                [
                    'address_id'          => $address->id,
                    'payment_schedule_id' => $paymentSchedule->id,
                ],
                $user->id
            );

            DB::commit();

            // Return success response with created data
            return response()->json([
                'message' => 'Address setup completed successfully',
                'data'    => [
                    'address'          => $address->load('landlordFinanceDetails'),
                    'payment_schedule' => $paymentSchedule,
                ],
            ], 201);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Address setup failed: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while setting up the address'], 500);
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

    private function handleFileUpload(Request $request, Address $address)
    {
        $tenancyAgreementPath = $address->tenancyAgreement;

        if ($request->hasFile('tenancyAgreement')) {
            try {
                if ($tenancyAgreementPath && Storage::disk('public')->exists($tenancyAgreementPath)) {
                    Storage::disk('public')->delete($tenancyAgreementPath);
                }

                $uniqueName = 'TA' . strtoupper(uniqid()) . '.' .
                $request->file('tenancyAgreement')->getClientOriginalExtension();

                $tenancyAgreementPath = $request->file('tenancyAgreement')->storeAs(
                    'tenancyAgreements',
                    $uniqueName,
                    'public'
                );
            } catch (\Exception $e) {
                \Log::error('File upload failed: ' . $e->getMessage());
                throw new \Exception('File upload failed');
            }
        }

        return $tenancyAgreementPath;
    }
}
