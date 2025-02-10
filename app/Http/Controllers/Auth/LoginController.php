<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Send OTP email to the user.
     */
    protected function sendOtpEmail(User $user, $otpCode)
    {
        Mail::send('emails.otp', ['otpCode' => $otpCode, 'firstName' => $user->first_name ?? "Customer"], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP Code');
        });
    }


    /**
     * Show the login form.
     */
    public function index()
    {
        return view('auth.login', [
            'pageTitle' => 'Intracard | Sign In',
        ]);
    }
    public function showLoginForm()
    {
        return view('auth.login', [
            'pageTitle' => 'Intracard | Sign In',
        ]);
    }

    /**
     * Handle the login request and send OTP.
     */
    public function login(Request $request)
    {
        // Check if the user is already authenticated
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user(),
                'message' => 'You are already logged in.',
            ], 200); // Return 200 status with authenticated user info
        }
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // Find the user by plaintext email
            $user = User::where('email', $request->email)->first();

            // Verify the user's credentials
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Log in the user
            Auth::login($user);

            // Generate a token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // Check if 2FA is enabled
            $enable2FA = DB::table('settings')->where('key', 'enable_2fa')->value('value') === 'true';

            if ($enable2FA) {
                // Generate and save OTP
                $otp = rand(100000, 999999);
                $user->update([
                    'otp_code' => $otp,
                    'otp_expires_at' => now()->addMinutes(5),
                ]);

                // Send OTP email
                $this->sendOtpEmail($user, $otp);

                // Return response for OTP redirection
                return response()->json([
                    'authenticated' => true, // User is authenticated
                    'user' => Auth::user(),
                    'token' => $token,
                    'otp_required' => true, // OTP verification is required
                    'redirect_url' => '/otp-verify', // OTP page
                    'message' => '2FA is enabled. Please verify your OTP.',
                ], 200);
            }

            return response()->json([
                'authenticated' => true,
                'user' => Auth::user(),
                'success' => true,
                'token' => $token,
                'status' => true,
                'redirect_url' => '/dashboard', // Use a relative URL
                'message' => 'Login successful!',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Login Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    public function status()
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'authenticated' => false,
            'user' => null,
        ]);
    }

    public function logout2(Request $request)
    {
        $user = User::where('email', Auth::user()->email)->first();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->update([
            'otp_verified' => false,
            'otp_code' => null, // Clear the OTP
            'otp_expires_at' => null, // Clear the expiry
        ]);

        return response()->json([
            'success' => true,
            'message' => 'You have been successfully logged out.',
            'redirect_url' => route('login'), // Redirect to login or any other page
        ], 200);
    }

    public function logout22(Request $request)
    {
        try {
            // Ensure the user is authenticated
            if (!Auth::guard('web')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No authenticated user found.',
                ], 401); // Unauthorized
            }

            // Get the authenticated user
            $user = Auth::guard('web')->user();

            // Perform the logout
            Auth::guard('web')->logout();

            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Update user's OTP status (optional, based on your requirement)
            $user->update([
                'otp_verified' => false,
                'otp_code' => null, // Clear the OTP
                'otp_expires_at' => null, // Clear the OTP expiry
            ]);

            return response()->json([
                'success' => true,
                'message' => 'You have been successfully logged out.',
                'redirect_url' => route('login'), // Redirect to login or any other page
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Logout Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function logout(Request $request)
    {
        try {
            // Ensure the user is authenticated
            if (!Auth::guard('web')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No authenticated user found.',
                ], 401); // Unauthorized
            }

            // Get the authenticated user
            $user = Auth::guard('web')->user();

            // Perform the logout
            Auth::guard('web')->logout();

            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Update user's OTP status (optional, based on your requirement)
            $user->update([
                'otp_verified' => false,
                'otp_code' => null, // Clear the OTP
                'otp_expires_at' => null, // Clear the OTP expiry
            ]);

            return response()->json([
                'success' => true,
                'message' => 'You have been successfully logged out.',
                'redirect_url' => '/login', // Redirect to login or any other page
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Logout Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }
}
