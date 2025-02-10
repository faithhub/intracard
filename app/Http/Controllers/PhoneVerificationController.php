<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\VerificationCode;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Validator;
// use App\Notifications\PhoneVerificationNotification;
// use Illuminate\Support\Facades\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class PhoneVerificationController extends Controller
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN')
        );
    }

    public function sendCode(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => [
                'required',
                'string',
                'regex:/^[2-9]\d{9}$/', // Exactly 10 digits, not starting with 1 or 0
                function ($attribute, $value, $fail) {
                    // Check cooldown period
                    $phone = preg_replace('/[^0-9]/', '', $value);
                    $lastSent = Cache::get('last_code_sent_' . $phone);
                    
                    if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
                        $timeLeft = 60 - now()->diffInSeconds($lastSent);
                        $fail("Please wait {$timeLeft} seconds before requesting another code.");
                    }
                },
            ]
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid 10-digit phone number.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Clean phone number
            $phone = '+234' . preg_replace('/[^0-9]/', '', $request->phone);
            
            // Check daily limit
            $dailyAttempts = Cache::get('daily_code_attempts_' . $phone, 0);
            if ($dailyAttempts >= 5) {
                return response()->json([
                    'message' => 'Maximum daily code requests exceeded',
                    'errors' => [
                        'phone' => ['Maximum number of attempts reached for today.']
                    ]
                ], 429);
            }

            // Generate code
            $code = "123456";
            // $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store code in cache
            Cache::put(
                'verification_code_' . $phone,
                [
                    'code' => $code,
                    'attempts' => 0
                ],
                now()->addMinutes(10)
            );

            // Send SMS via Twilio
            // $message = $this->twilio->messages->create(
            //     $phone,
            //     [
            //         'from' => config('services.twilio.phone_number'),
            //         'body' => "Your Intracard verification code is: {$code}. This code will expire in 10 minutes."
            //     ]
            // );

            // Update rate limiting
            Cache::put('last_code_sent_' . $phone, now(), now()->addMinutes(1));
            Cache::put(
                'daily_code_attempts_' . $phone, 
                $dailyAttempts + 1, 
                now()->endOfDay()
            );

            return response()->json([
                'message' => 'Verification code sent successfully',
                'data' => [
                    'phone' => $phone,
                    'attempts_remaining' => 5 - $dailyAttempts - 1,
                    'expires_in' => 600,
                    // 'message_sid' => $message->sid
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Twilio error:', [
                'error' => $e->getMessage(),
                'phone' => $request->phone
            ]);

            return response()->json([
                'message' => 'Failed to send verification code',
                'errors' => [
                    'phone' => ['Unable to send SMS. Please try again later.']
                ]
            ], 500);
        }
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $phone = '+234' . preg_replace('/[^0-9]/', '', $request->phone);
            $cacheKey = 'verification_code_' . $phone;
            $verification = Cache::get($cacheKey);

            if (!$verification) {
                return response()->json([
                    'message' => 'Verification failed',
                    'errors' => [
                        'code' => ['Verification code has expired or not found.']
                    ]
                ], 400);
            }

            if ($verification['attempts'] >= 3) {
                Cache::forget($cacheKey);
                return response()->json([
                    'message' => 'Verification failed',
                    'errors' => [
                        'code' => ['Too many invalid attempts. Please request a new code.']
                    ]
                ], 400);
            }

            if ($verification['code'] !== $request->code) {
                // Increment attempts
                $verification['attempts']++;
                Cache::put($cacheKey, $verification, now()->addMinutes(10));

                return response()->json([
                    'message' => 'Invalid verification code',
                    'errors' => [
                        'ggg' => $verification['code'],
                        'gggrr' => $request->code,
                        'code' => ['Invalid code. ' . (3 - $verification['attempts']) . ' attempts remaining.']
                    ]
                ], 400);
            }

            // Success - clear the verification code
            Cache::forget($cacheKey);

            return response()->json([
                'message' => 'Phone number verified successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Verification error:', [
                'error' => $e->getMessage(),
                'phone' => $request->phone
            ]);

            return response()->json([
                'message' => 'Verification failed',
                'errors' => [
                    'general' => ['An error occurred during verification.']
                ]
            ], 500);
        }
    }
}