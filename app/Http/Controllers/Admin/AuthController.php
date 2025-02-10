<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // dd(Hash::make('Intracard@1'));
        return view('admin.auth.login', [
            'pageTitle' => 'Intracard Admin | Sign In',
        ]);
    }

    /**
     * Handle the login request and send OTP.
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // Retrieve admin by email
            $admin = Admin::where('email', $request->email)->first();

            // Check if admin exists and password matches
            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials.',
                ], 401); // 401 Unauthorized
            }

            // Optional: Ensure admin is active (if your `admins` table has an `is_active` field)
            // if (isset($admin->is_active) && !$admin->is_active) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Your account is inactive. Please contact support.',
            //     ], 403); // 403 Forbidden
            // }

            // Log in the admin
            Auth::guard('admin')->login($admin);

            // Return successful login response
            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'redirect_url' => route('admin.dashboard'), // Change to your admin dashboard route
            ], 200);

        } catch (\Throwable $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. ' . $e->getMessage(),
                'error' => $e->getMessage(), // Optionally include error for debugging
            ], 500); // 500 Internal Server Error
        }
    }

    // public function login(Request $request)
    // {
    //     // Validate input
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required|min:8',
    //     ]);

    //     // Handle validation failures
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation errors occurred.',
    //             'errors' => $validator->errors(),
    //         ], 422); // 422 Unprocessable Entity for validation errors
    //     }

    //     // If validation passes, proceed to register the user
    //     try {
    //         // Attempt to authenticate the user using email and password
    //         $user = Admin::where('email', $request->email)->first();

    //         if (!$user || !Hash::check($request->password, $user->password)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid credentials',
    //             ], 401);
    //         }

    //         // Check if OTP is verified
    //         // if (!$user->otp_verified) {
    //         //     // Generate OTP
    //         //     $otpCode = rand(100000, 999999); // 6-digit OTP // Generate 6-character OTP
    //         //     $otpExpiresAt = Carbon::now()->addMinutes(10); // OTP valid for 10 minutes

    //         //     // Save OTP and expiration to the user's record
    //         //     $user->update([
    //         //         'otp_code' => $otpCode,
    //         //         'otp_expires_at' => $otpExpiresAt,
    //         //         'otp_verified' => false,
    //         //     ]);

    //         //     // Send OTP to the user's email (You need to implement `sendOtpEmail`)
    //         //     $this->sendOtpEmail($user, $otpCode);

    //         // }

    //         // If OTP is already verified, log the user in
    //         Auth::login($user);

    //         return response()->json([
    //             'success' => true,
    //             'status' => true,
    //             'redirect_url' => route('dashboard'), // Change this to your dashboard route
    //             'message' => 'Login successful!',
    //         ], 200);

    //     } catch (\Throwable $th) {
    //         // Handle any errors during user creation
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred: ' . $th->getMessage(),
    //         ], 500); // 500 Internal Server Error
    //     }
    // }

    public function logout(Request $request)
    {
        // Check if the user is authenticated as an admin
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();

            // Log the admin out
            Auth::guard('admin')->logout();

            // Invalidate the session and regenerate the CSRF token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Optionally clear admin-specific data
            if ($admin) {
                $admin->update([
                    'otp_verified' => false, // Mark OTP as not verified
                    'otp_code' => null, // Clear the OTP code
                    'otp_expires_at' => null, // Clear OTP expiry time
                ]);
            }

            // Return response for successful logout
            return response()->json([
                'success' => true,
                'message' => 'You have been successfully logged out.',
                'redirect_url' => route('admin.login'), // Redirect to admin login page
            ], 200);
        }

        // If no admin is authenticated, return an error response
        return response()->json([
            'success' => false,
            'message' => 'No admin is currently logged in.',
        ], 401); // 401 Unauthorized
    }

}
