<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillHistory;
use App\Models\Card;
use App\Models\PaymentSchedule;
use App\Models\Wallet;
use App\Models\WalletAllocation;
use App\Models\WalletTransaction;
use App\Notifications\PaymentReminderNotification;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function fetch()
    {
        $billHistories = BillHistory::with(['bill', 'card'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($history) {
                return [
                    'id' => $history->id,
                    'uuid' => $history->uuid,
                    'bill_type' => $history->bill->name ?? 'N/A',
                    'amount' => $history->amount,
                    'status' => $history->status,
                    'provider' => $history->provider,
                    'due_date' => $history->due_date,
                    'account_number' => $history->account_number,
                    'frequency' => $history->frequency,
                    'car_model' => $history->car_model,
                    'car_year' => $history->car_year,
                    'car_vin' => $history->car_vin,
                    'phone' => $history->phone,
                    'card_info' => [
                        'type' => $history->card->type,
                        'last_four' => substr($history->card->number, -4),
                    ],
                    'created_at' => $history->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json($billHistories);
    }

    public function destroy($uuid)
    {
        $billHistory = BillHistory::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $billHistory->delete();

        return response()->json([
            'message' => 'Bill history deleted successfully'
        ]);
    }

    public function index()
    {
        try {
            $data['dashboard_title'] = "Credit Card";
            // Retrieve the encrypted billing data from the database
            // $billing_details = Billing::where("user_id", Auth::user()->id)->first();

            // // Step 1: Laravel Decryption to retrieve the client-side encrypted data
            // $encrypted_data = Crypt::decrypt($billing_details->encrypted_billing_data);
            // $clientEncryptedData = $encrypted_data['data'];
            // $iv = $encrypted_data['iv'];

            // // Step 2: Client-side Decryption using OpenSSL (ensure you use the correct cipher and key)
            // $cipher = 'aes-256-cbc'; // replace with the actual cipher used for client encryption
            // $key = 'your_client_side_secret_key'; // replace with the actual client-side secret key

            // $decrypted_data = openssl_decrypt($clientEncryptedData, $cipher, $key, OPENSSL_RAW_DATA, $iv);

            // dd($billing_details->encrypted_billing_data, $decrypted_data);
            return view('user.billing', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name'        => ['required', 'string', 'min:3', 'max:255'],
                'number'      => ['required', 'string', 'min:16', 'max:16', 'regex:/^\d{16}$/'],
                'expiryMonth' => ['required', 'integer', 'between:1,12'],
                'expiryYear'  => ['required', 'integer', 'min:' . date('Y')],
                'cvv'         => ['required', 'string', 'min:3', 'max:4', 'regex:/^\d{3,4}$/'],
            ]);

            // Determine card type based on number
            $cardType = $this->determineCardType($validated['number']);

            // Check if this is the user's first card (will be primary)
            $isPrimary = ! Card::where('user_id', auth()->id())
                ->where('status', 'active')
                ->exists();

            // Mask card number (keep last 4 digits)
            $maskedNumber = str_pad(
                substr($validated['number'], -4),
                strlen($validated['number']),
                '*',
                STR_PAD_LEFT
            );

            // Create new card record
            $card = Card::create([
                'user_id'      => auth()->id(),
                'uuid'         => Str::uuid(),
                'token'        => Crypt::encryptString($validated['number']), // Encrypt full card number
                'card_number'  => $maskedNumber,
                'name_on_card' => $validated['name'],
                'type'         => $cardType,
                'expiry_month' => $validated['expiryMonth'],
                'expiry_year'  => $validated['expiryYear'],
                'cvv'          => Crypt::encryptString($validated['cvv']), // Encrypt CVV
                'status'       => 'active',
                'is_primary'   => $isPrimary,
            ]);

            // If this is set as primary, update other cards
            if ($isPrimary) {
                Card::where('user_id', auth()->id())
                    ->where('id', '!=', $card->id)
                    ->update(['is_primary' => false]);
            }
            // Create notification
            $this->notificationService->createCardNotification('card_added', [
                'last4'   => substr($maskedNumber, -4),
                'card_id' => $card->id,
            ]);
            return response()->json([
                'message' => 'Card added successfully',
                'card'    => [
                    'id'           => $card->id,
                    'uuid'         => $card->uuid,
                    'name'         => $card->name_on_card,
                    'number'       => $maskedNumber,
                    'type'         => $card->type,
                    'expiry_month' => $card->expiry_month,
                    'expiry_year'  => $card->expiry_year,
                    'is_primary'   => $card->is_primary,
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the card',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function determineCardType(string $number): string
    {
        // Visa
        if (preg_match('/^4/', $number)) {
            return 'visa';
        }

        // Mastercard
        if (preg_match('/^5[1-5]/', $number)) {
            return 'mastercard';
        }

        // American Express
        if (preg_match('/^3[47]/', $number)) {
            return 'amex';
        }

        // Discover
        if (preg_match('/^6(?:011|5)/', $number)) {
            return 'discover';
        }

        return 'unknown';
    }

    public function billTypes2(): JsonResponse
    {
        // Get the authenticated user's account goal
        $accountGoal = auth()->user()->account_goal;

        // Get base bills query
        $query = Bill::select('id', 'value', 'name');

        // Filter bills based on account goal
        if ($accountGoal === 'rent') {
            // Include rent and all other bills except mortgage
            $query->where(function ($q) {
                $q->where('value', '!=', 'mortgage')
                    ->orWhere('value', 'rent');
            });
        } elseif ($accountGoal === 'mortgage') {
            // Include mortgage and all other bills except rent
            $query->where(function ($q) {
                $q->where('value', '!=', 'rent')
                    ->orWhere('value', 'mortgage');
            });
        }

        $bills = $query->get();

        return response()->json($bills);
    }
    public function billTypes(): JsonResponse
    {
        $user = auth()->user();
        $accountGoal = $user->account_goal;
    
        // Get user's active bill histories
        $activeBills = BillHistory::where('user_id', $user->id)
            ->where('status', 'active')
            ->pluck('bill_id')
            ->toArray();
    
        // Get base bills query
        $query = Bill::select('id', 'value', 'name');
    
        // Filter bills based on account goal
        if ($accountGoal === 'rent') {
            $query->where(function ($q) {
                $q->where('value', '!=', 'mortgage')
                    ->orWhere('value', 'rent');
            });
        } elseif ($accountGoal === 'mortgage') {
            $query->where(function ($q) {
                $q->where('value', '!=', 'rent')
                    ->orWhere('value', 'mortgage');
            });
        }
    
        // Get bills and add 'is_setup' flag
        $bills = $query->get()->map(function ($bill) use ($activeBills, $accountGoal) {
            $isSetup = in_array($bill->id, $activeBills);
            
            // Handle mortgage/rent default setup based on account goal
            if (($bill->value === 'mortgage' && $accountGoal === 'mortgage') || 
                ($bill->value === 'rent' && $accountGoal === 'rent')) {
                $isSetup = true;
            }
    
            return [
                'id' => $bill->id,
                'value' => $bill->value,
                'name' => $bill->name,
                'is_setup' => $isSetup
            ];
        });
    
        return response()->json($bills);
    }

    public function deleteCard(Request $request, $cardId)
    {
        try {
            $request->validate([
                'password' => 'required|string',
            ]);

            $user = Auth::user();

            if (! Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Incorrect password'], 401);
            }

            // Find the card
            $card = Card::where('id', $cardId)
                ->where('user_id', $user->id)
                ->first();

            if (! $card) {
                return response()->json(['message' => 'Card not found'], 404);
            }

            $last4 = substr($card->card_number, -4);
            // Delete the card
            $card->status = 'removed';
            $card->save();
            // Or if you want to actually delete: $card->delete();

            // Create notification
            $this->notificationService->createCardNotification('card_deleted', [
                'last4'   => $last4,
                'card_id' => $card->id,
            ]);

            return response()->json(['message' => 'Card deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the card',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function showCards()
    {
        $cards = Card::where(['user_id' => Auth::user()->id, 'status' => 'active'])->get()
            ->map(function ($card) {
                try {
                    $decryptedCvv = Crypt::decryptString($card->cvv);
                    $maskedCvv    = str_repeat('*', strlen($decryptedCvv) - 1) . substr($decryptedCvv, -1);
                } catch (\Exception $e) {
                    $maskedCvv = '***';
                    \Log::error('Error decrypting CVV for card ID: ' . $card->id);
                }

                return [
                    'id'          => $card->id,
                    'name'        => $card->name_on_card,
                    'number'      => '**** **** **** ' . substr($card->card_number, -4),
                    'expiryMonth' => $card->expiry_month,
                    'expiryYear'  => $card->expiry_year,
                    'cvv'         => $maskedCvv, // Show masked CVV
                    'limit'       => '1000',
                    'type'        => $card->type,
                    'is_primary'  => $card->is_primary,
                    'logoUrl'     => $this->getCardLogo($card->type),
                ];
            });

        return response()->json($cards);
        // return view('user.api.cards.index', compact('cards'));
    }

    public function storeBillHistory2(Request $request)
    {
        try {
            // Get the current authenticated user
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'message' => 'Unauthorized: Please log in to continue',
                ], 401);
            }

            // Base validation rules
            $rules = [
                'bill_id'        => 'nullable|exists:bills,id',
                // 'user_id' => 'required|exists:users,id',
                'card_id'        => 'required|exists:cards,id',
                'provider'       => 'nullable|string|max:255',
                'due_date'       => 'nullable|date',
                'account_number' => 'nullable|string|max:255',
            ];

            // Add dynamic rules based on bill_type
            if ($request->bill_type === 'carBill') {
                $rules = array_merge($rules, [
                    'frequency' => 'required|string|max:255',
                    'car_model' => 'required|string|max:255',
                    'car_year'  => 'required|string|max:4',
                    'car_vin'   => 'required|string|max:255',
                    'amount'    => 'required|numeric', // Add amount rule directly for carBill
                ]);
            } elseif ($request->bill_type === 'phoneBill') {
                $rules['phone']  = 'required|string|regex:/^\+1\d{10}$/';
                $rules['amount'] = 'required|numeric'; // Add amount rule for phoneBill
            } elseif ($request->bill_type === 'internetBill') {
                $rules['provider'] = 'required|string|max:255';
                $rules['amount']   = 'required|numeric'; // Add amount rule for internetBill
            } else {
                // Default amount rule for other bill types
                $rules['amount'] = 'required|numeric';
            }

            // Validate the request dynamically
            $validated = $request->validate($rules);

            // Add UUID and default status
            // Add the current user's ID and additional fields
            $validated['user_id'] = $user->id;
            $validated['uuid']    = (string) Str::uuid();
            $validated['status']  = 'active';

            // Save the bill history
            $billHistory = BillHistory::create($validated);

            // Return success response
            return response()->json([
                'message' => 'Bill history saved successfully',
                'data'    => $billHistory,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a structured validation error response
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Return a general error response
            return response()->json([
                'message' => 'An error occurred while saving the bill history',
                'error'   => $e->getMessage(),
            ], 500);
        }

    }

    public function storeBillHistory(Request $request)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Get the current authenticated user
            $user = Auth::user();

            if (! $user) {
                return response()->json([
                    'message' => 'Unauthorized: Please log in to continue',
                    // 'request' => $request->all(),
                ], 401);
            }

            // Base validation rules
            $rules = [
                'bill_id'        => 'nullable|exists:bills,id',
                'card_id'        => 'required',
                // 'card_id' => 'required|exists:cards,id',
                'provider'       => 'nullable|string|max:255',
                'due_date'       => 'nullable|date',
                'account_number' => 'nullable|string|max:255',
            ];

            // Add dynamic rules based on bill_type
            if ($request->bill_type === 'carBill') {
                $rules = array_merge($rules, [
                    'frequency' => 'required|string|max:255',
                    'car_model' => 'required|string|max:255',
                    'car_year'  => 'required|string|max:4',
                    'car_vin'   => 'required|string|max:255',
                    'amount'    => 'required|numeric',
                ]);
            } elseif ($request->bill_type === 'phoneBill') {
                // $rules['phone']  = 'required|string|regex:/^\+1\d{10}$/';
                $rules['phone'] = 'required|string|regex:/^\d{10}$/';
                $rules['amount'] = 'required|numeric';
            } elseif ($request->bill_type === 'internetBill') {
                $rules['provider'] = 'required|string|max:255';
                $rules['amount']   = 'required|numeric';
            } else {
                $rules['amount'] = 'required|numeric';
            }

            // Validate the request dynamically
            $validated = $request->validate($rules);

            $card = Card::where('id', $request->card_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$card) {
                return response()->json([
                    'message' => 'The selected card is invalid or does not belong to you.',
                ], 401);
            }

            // Add additional fields
            $validated['user_id'] = $user->id;
            $validated['uuid']    = (string) Str::uuid();
            $validated['status']  = 'active';

            // dd($validated);

            // Save the bill history
            $billHistory = BillHistory::create($validated);

                                                              // Save the recurring billing schedule
            $frequency    = $request->frequency ?? 'monthly'; // monthly or bi-weekly
            $dueDate      = Carbon::parse($request->due_date);
            $startDate    = Carbon::now();            // Start from today
            $endDate      = Carbon::now()->addYear(); // End 1 year from now
            $recurringDay = $dueDate->day;

            $paymentSchedule = PaymentSchedule::create([
                'user_id'         => $user->id,
                                                           // 'payment_type' => $request->bill_type, // Use bill_type as payment_type
                'payment_type'    => 'bill',               // Use bill_type as payment_type
                'recurring_day'   => $recurringDay,        // Day of the month for monthly billing
                'amount'          => $validated['amount'], // Include the amount
                'bill_history_id' => $billHistory->id,     // Link to the bill history ID
                'duration_from'   => $startDate,
                'duration_to'     => $endDate, // Default: 1 year of recurring billing
                'status'          => 'active',
                'frequency'       => $frequency, // Add frequency to PaymentSchedule
                'reminder_dates'  => null,       // Initially null
            ]);

            // Generate recurring dates and save reminders
            // $recurringDates = $this->generateRecurringDates($dueDate, $frequency, now()->addYear());
            // $recurringDates = $paymentSchedule->generateRecurringDates($startDate, $endDate, $recurringDay, $frequency);

            // Generate reminders using the model method
            $reminders = $paymentSchedule->generateReminders();

            // Save the reminders in the database as JSON
            $paymentSchedule->update(['reminder_dates' => json_encode($reminders)]);

            // Queue email notifications for each reminder
            foreach ($reminders as $paymentDate => $reminderDates) {
                foreach ($reminderDates as $key => $date) {
                    if (Carbon::parse($date)->isFuture()) {
                        Notification::route('mail', $user->email)
                            ->notify(new PaymentReminderNotification($paymentSchedule, $key));
                    }
                }
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'message' => 'Bill history and payment schedule saved successfully',
                'data'    => [
                    'billHistory'     => $billHistory,
                    'paymentSchedule' => $paymentSchedule,
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback the transaction on validation error
            DB::rollBack();

            // Return a structured validation error response
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Rollback the transaction on any other error
            DB::rollBack();

            // Return a general error response
            return response()->json([
                'message' => 'An error occurred while saving the bill history',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function getCardDetails($cardId)
    {
        $dummyCardData = [
            1 => [
                'id'          => 1,
                'name'        => 'John Doe',
                "type"        => "Mastercard",
                "limit"       => 1000,
                'number'      => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear'  => '2025',
                'cvv'         => '123',
                'is_primary'  => true,
                'logoUrl'     => $this->getCardLogo('Visa'),
            ],
            2 => [
                'id'          => 2,
                'name'        => 'Jane Smith',
                "type"        => "Mastercard",
                "limit"       => 1000,
                'number'      => '5500000000000004',
                'expiryMonth' => '9',
                'expiryYear'  => '2024',
                'cvv'         => '456',
                'is_primary'  => false,
                'logoUrl'     => $this->getCardLogo('Mastercard'),
            ],
            3 => [
                'id'          => 3,
                'name'        => 'Marcus Morris',
                "type"        => "Visa",
                "limit"       => 1000,
                'number'      => '340000000000009',
                'expiryMonth' => '6',
                'expiryYear'  => '2026',
                'cvv'         => '789',
                'is_primary'  => false,
                'logoUrl'     => $this->getCardLogo('Visa'),
            ],
        ];

        $card = Card::where(['user_id' => Auth::user()->id, 'id' => $cardId])
            ->get()
            ->map(function ($card) {
                return [
                    'id'                 => $card->id,
                    'name_on_card'       => $card->name_on_card,
                    'masked_card_number' => '**** **** **** ' . substr($card->card_number, -4), // Masked card number
                    'expiry_month'       => $card->expiry_month,
                    'expiry_year'        => $card->expiry_year,
                    'is_primary'         => $card->is_primary,
                ];
            });
        // Check if cardId exists in dummy data
        if (! $card) {
            return response()->json([
                'message' => 'Card not found',
            ], 404);
        }

        // Return card details
        return response()->json($card, 200);
    }

    public function getCardDetails2($cardId)
    {
        // try {
        //     // Fetch card details from the database
        //     $card = Card::where('id', $cardId)
        //         ->where('user_id', auth()->id()) // Ensure the card belongs to the authenticated user
        //         ->first();

        //     // Check if the card exists
        //     if (!$card) {
        //         return response()->json([
        //             'message' => 'Card not found or unauthorized access.',
        //         ], 404);
        //     }

        //     // Return the card details as JSON
        //     return response()->json([
        //         'name' => $card->name_on_card,
        //         'number' => $card->masked_card_number, // Ensure to mask the card number (e.g., **** **** **** 1234)
        //         'expiryMonth' => $card->expiry_month,
        //         'expiryYear' => $card->expiry_year,
        //         'cvv' => '***', // CVV should never be sent back for security reasons
        //     ], 200);
        // } catch (Exception $e) {
        //     // Log the exception
        //     \Log::error("Error fetching card details: {$e->getMessage()}");

        //     // Return a generic error response
        //     return response()->json([
        //         'message' => 'An error occurred while fetching the card details. Please try again later.',
        //     ], 500);
        // }
    }

    public function editForm($id)
    {
        // if (!is_numeric($id)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Invalid card ID.'
        //     ], 400); // Return 400 status for bad request
        // }

        // $card = Card::find($id);

        // if (!$card) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Card not found.'
        //     ], 404);
        // }

        $dummyCardData = [
            1 => [
                'id'          => 1,
                'name'        => 'John Doe',
                "type"        => "Mastercard",
                "limit"       => 1000,
                'number'      => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear'  => '2025',
                'cvv'         => '123',
                'is_primary'  => true,
                'logoUrl'     => $this->getCardLogo('Visa'),
            ],
            2 => [
                'id'          => 2,
                'name'        => 'Jane Smith',
                "type"        => "Mastercard",
                "limit"       => 1000,
                'number'      => '5500000000000004',
                'expiryMonth' => '9',
                'expiryYear'  => '2024',
                'cvv'         => '456',
                'is_primary'  => false,
                'logoUrl'     => $this->getCardLogo('Mastercard'),
            ],
            3 => [
                'id'          => 3,
                'name'        => 'Marcus Morris',
                "type"        => "Visa",
                "limit"       => 1000,
                'number'      => '340000000000009',
                'expiryMonth' => '6',
                'expiryYear'  => '2026',
                'cvv'         => '789',
                'is_primary'  => false,
                'logoUrl'     => $this->getCardLogo('Visa'),
            ],
        ];

        // Check if cardId exists in dummy data
        if (! array_key_exists($id, $dummyCardData)) {
            return response()->json([
                'message' => 'Card not found',
            ], 404);
        }

        $card = json_decode(json_encode($dummyCardData[$id]));

        // dd($card);

        return view('user.modals.editCard', compact('card'));
    }

    public function update(Request $request, $id)
    {
        // Check if the card exists
        // $card = Card::find($id);

        // if (!$card) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Card not found.'
        //     ], 404); // Return 404 status code
        // }

        // Validate the input data
        $validator = Validator::make($request->all(), [
            'cardName'   => 'required|string|max:255',
            'cardNumber' => 'required|digits:16',
            'expiryDate' => 'required|regex:/^\d{2}\/\d{4}$/', // MM/YYYY format
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }

        // Update the card details
        // $card->name = $request->input('cardName');
        // $card->number = $request->input('cardNumber');
        // [$month, $year] = explode('/', $request->input('expiryDate'));
        // $card->expiry_month = $month;
        // $card->expiry_year = $year;
        // $card->save();

        return response()->json([
            'success' => true,
            'message' => 'Card updated successfully!',
                             // 'data' => $card, // Optional: return updated card data
            'data'    => [], // Optional: return updated card data
        ]);
    }

    // Helper method to get card logos
    protected function getCardLogo($type)
    {
        switch (strtolower($type)) {
            case 'visa':
                return asset('assets/cards/visa.webp');
            case 'mastercard':
                return asset('assets/cards/mastercard.png');
            case 'debit':
                return asset('assets/cards/mastercard.png');
            default:
                return asset('assets/cards/default-card.png');
        }
    }

    public function wallet()
    {
        try {
            $data['dashboard_title'] = "Wallet";
            return view('user.wallet', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function getWalletData(Request $request)
    {
        try {
            // Fetch authenticated user
            $user = Auth::user();

            // Fetch wallet details
            $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

            // Fetch wallet allocations
            $allocations = WalletAllocation::where('wallet_id', $wallet->id)
                ->with('bill') // Assuming the allocation has a relationship to the Bill model
                ->get(['id', 'allocated_amount', 'spent_amount', 'remaining_amount', 'bill_id']);

            // Fetch wallet transactions
            $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                ->join('bills', 'wallet_transactions.bill_id', '=', 'bills.id') // Join with bills table
                ->orderBy('wallet_transactions.created_at', 'desc')
                ->get([
                    'wallet_transactions.uuid',
                    'wallet_transactions.amount',
                    'wallet_transactions.charge',
                    'wallet_transactions.status',
                    'wallet_transactions.type',
                    'wallet_transactions.details',
                    'wallet_transactions.created_at',
                    'bills.name as service_name', // Fetch service name from bills
                ])
                ->transform(function ($transaction) {
                    $transaction->amount = '$' . number_format($transaction->amount, 2); // Format amount as CAD
                    return $transaction;
                });

            $transactions_table = WalletTransaction::where('wallet_transactions.wallet_id', $wallet->id)
                ->join('bills', 'wallet_transactions.bill_id', '=', 'bills.id') // Join with bills table
                ->orderBy('wallet_transactions.created_at', 'desc')             // Ensure correct table reference for created_at
                ->get([
                    'wallet_transactions.uuid',
                    'wallet_transactions.amount',
                    'wallet_transactions.charge',
                    'wallet_transactions.status',
                    'wallet_transactions.type',
                    'wallet_transactions.details',
                    'wallet_transactions.created_at',
                    'bills.name as service_name', // Get the service name from bills
                ]);

            // Format and return response
            return response()->json([
                'success' => true,
                'data'    => [
                    'wallet_balance'     => $wallet->balance,
                    'allocations'        => $allocations,
                    // 'transactions' => $transactions,
                    'wallet'             => $wallet,
                    'transactions_table' => $transactions_table,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch wallet data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle the fund wallet request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fundWallet(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'amount'  => 'required|numeric|min:1',
            'service' => 'required|exists:bills,id',
            // 'card' => 'required|exists:cards,id',
            'card'    => 'required',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction(); // Start a transaction

            // Fetch wallet for the authenticated user
            $user   = Auth::user();
            $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

            // Check if the selected card belongs to the user
            $service = Bill::where('id', $request->service)
                ->first();

            if (! $service) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['service' => 'The selected service is invalid or does not exist.'],
                ], 403);
            }

            // Check if the selected card belongs to the user
            $card = Card::where('id', $request->card)
                ->where('user_id', $user->id)
                ->first();

            if (! $card) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['card' => 'The selected card is invalid or does not belong to you.'],
                ], 403);
            }

            // Process the fund operation
            $amount = (float) $request->amount;
            $wallet->balance += $amount;
            $wallet->save();

            // Check if a wallet allocation already exists for the user, wallet, and bill
            $existingAllocation = WalletAllocation::where('user_id', $user->id)
                ->where('wallet_id', $wallet->id)
                ->where('bill_id', $this->getBillIdById($request->service))
                ->first();

            if ($existingAllocation) {
// Update the existing allocation
                $existingAllocation->allocated_amount += $amount;
                $existingAllocation->remaining_amount += $amount;
                $existingAllocation->save();
                $allocation = $existingAllocation;
            } else {
// Create a new allocation
                $allocation = WalletAllocation::create([
                    'user_id'          => $user->id,
                    'wallet_id'        => $wallet->id,
                    'bill_id'          => $this->getBillIdById($request->service),
                    'allocated_amount' => $amount,
                    'spent_amount'     => 0,
                    'remaining_amount' => $amount,
                ]);
            }

            // Allocate the funds to the selected service
            // $allocation = WalletAllocation::create([
            //     'user_id'          => $user->id,
            //     'wallet_id'        => $wallet->id,
            //     'bill_id'          => $this->getBillIdById($request->service), // Convert service value to bill ID
            //     'allocated_amount' => $amount,
            //     'spent_amount'     => 0,
            //     'remaining_amount' => $amount,
            // ]);

                                                     // Calculate the charge as 5% of the amount
            $charge = round(($amount * 5) / 100, 2); // Round to 2 decimal places

            // Save transaction to wallet_transactions table
            $transaction = WalletTransaction::create([
                'user_id'   => $user->id,
                'bill_id'   => $this->getBillIdById($request->service), // Corrected extra space in 'bill_id'
                'wallet_id' => $wallet->id,
                'uuid'      => (string) Str::uuid(),
                'amount'    => $amount,     // Original amount
                'charge'    => $charge,     // Save the calculated charge
                'status'    => 'completed', // Set transaction as completed
                'type'      => 'inbound',   // Inbound transaction
                'details'   => json_encode([
                    'service'       => $request->service,
                    'allocation_id' => $allocation->id,
                    'card_id'       => $request->card,
                ]), // Save details in JSON format
            ]);

            DB::commit(); // Commit the transaction if all operations succeed

            // Fetch updated transactions
            $transactions_table = WalletTransaction::where('wallet_transactions.wallet_id', $wallet->id)
                ->join('bills', 'wallet_transactions.bill_id', '=', 'bills.id') // Join with bills table
                ->orderBy('wallet_transactions.created_at', 'desc')             // Ensure correct table reference for created_at
                ->get([
                    'wallet_transactions.uuid',
                    'wallet_transactions.amount',
                    'wallet_transactions.charge',
                    'wallet_transactions.status',
                    'wallet_transactions.type',
                    'wallet_transactions.details',
                    'wallet_transactions.created_at',
                    'bills.name as service_name', // Get the service name from bills
                ]);

                                                         // After successful funding
            $cardLast4 = substr($card->card_number, -4); // This will extract '8658' from '************8658'

            $this->notificationService->createWalletNotification('wallet_funded', [
                'amount'         => $request->amount,
                'service'        => $service->name,
                'card_last4'     => $cardLast4,
                'transaction_id' => $transaction->id,
            ]);

            // Or if funding fails
            // $this->notificationService->createWalletNotification('fund_failed', [
            //     'amount' => $request->amount,
            //     'service' => $service->name,
            //     'card_last4' => $card->last4,
            //     'error' => $error->getMessage()
            // ]);

            // Respond with updated wallet balance and transactions
            return response()->json([
                'success'      => true,
                'newBalance'   => $wallet->balance,
                'transactions' => $transactions_table,
                'message'      => 'Wallet funded successfully.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback all changes if an error occurs
                            // Send app notification for failed payment
            $this->notificationService->createWalletNotification('fund_failed', [
                'amount'      => $request->amount,
                'payment_for' => $request->payment_for,
                'error'       => $e->getMessage(),
            ]);
            dd($e);
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request. Please try again.',
                'error'   => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Get bill ID by its value.
     *
     * @param string $value
     * @return int
     */
    private function getBillIdById($value)
    {
        $bill = Bill::where('id', $value)->firstOrFail();
        return $bill->id;
    }

    private function getBillName($value)
    {
        $bill = Bill::where('id', $value)->firstOrFail();
        return $bill->name;
    }
}
