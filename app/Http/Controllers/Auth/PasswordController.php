<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function showResetPasswordForm()
    {
        return view('auth.password.sendLink');
    }
    public function sendResetLink(Request $request)
    {
        // Validate the email input
        $request->validate(['email' => 'required|email']);

        // Send the reset password link to the user's email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Return response based on the result
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('login'),
                'status' => 'success',
                'message' => 'Reset password link sent to your email.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => __($status),
            ], 400);
        }
    }

    // Show the password reset form
    public function showResetForm(Request $request)
    {
        // Retrieve the 'token' and 'email' from the query string
        $token = $request->query('token');
        $email = $request->query('email');

        // Check if both parameters are provided (optional validation step)
        if (!$token || !$email) {
            abort(404, 'Invalid token or email.');
        }

        // Pass the token and email to the view
        return view('auth.password.reset', compact('token', 'email'));
    }

    public function reset(Request $request)
    {
        // Validate the incoming reset password request
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed', // Make sure passwords match
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity for validation errors
        }

        try {
            //code...
            // Attempt to reset the user's password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    // Update the password on the user model
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            // Return response based on the result
            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'status' => true,
                    'message' => __($status),
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => __($status),
                ], 400);
            }
        } catch (\Throwable $th) {
            // Handle any errors during user creation
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500); // 500 Internal Server Error
            //throw $th;
        }
    }

}
