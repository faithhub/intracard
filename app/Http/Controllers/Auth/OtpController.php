<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        try {
            //code...
            return view('auth.otp-verify');
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function resendOtp(Request $request)
    {
        // Logic to generate and send new OTP to the user
        $user = User::where('email', Auth::user()->email)->first();
        // Assuming you have a method to generate and send OTP
        $otpCode = $this->generateAndSendOtp($user);

        $this->sendOtpEmail($user, $otpCode);

        // Return success response
        return response()->json(['success' => true, 'message' => 'OTP resent successfully!'], 200);
    }

    private function generateAndSendOtp($user)
    {
        // Logic to generate OTP
        $otpCode = rand(100000, 999999); // 6-digit OTP
        $otpExpiresAt = Carbon::now()->addMinutes(10); // OTP valid for 10 minutes

        $user->save();
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpiresAt,
            'otp_verified' => false,
        ]);

        // Logic to send OTP (e.g., via email, SMS)
        // Example: Mail::to($user->email)->send(new OtpMail($otp));
        return $otpCode;
    }

    protected function sendOtpEmail(User $user, $otpCode)
    {
        Mail::send('emails.otp', ['otpCode' => $otpCode, 'firstName' => $user->first_name ?? "Customer"], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP Code');
        });
    }

    public function verifyOtp(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'otp_code' => 'required',
            ]);

            // Handle validation failures
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors(),
                ], 422); // 422 Unprocessable Entity for validation errors
            }

            $user = User::where('email', Auth::user()->email)->first();

            // Check if OTP matches and is not expired
            if ($user->otp_code === $request->otp_code && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
                // Update OTP status to verified
                $user->update([
                    'otp_verified' => true,
                    'otp_code' => null, // Clear the OTP
                    'otp_expires_at' => null, // Clear the expiry
                ]);

                return response()->json([
                    'authenticated' => true,
                    'otp_verified' => $user->otp_verified,
                    'redirect_url' => '/dashboard',            
                    'success' => true,
                    'message' => 'Login successful!',
                ], 200); // 422 Unprocessable Entity for validation errors
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid or expired OTP.",
                ], 409); // 422 Unprocessable Entity for validation errors
            }

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'errors' => "An error occur",
            ], 409); // 422 Unprocessable Entity for validation errors

        }

    }
}
