<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Attempt to send the password reset link
        $response = Password::sendResetLink($request->only('email'));

        // Send appropriate AJAX response
        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent successfully.'], 200);
        } else {
            return response()->json(['message' => 'Failed to send reset link. Please check the email address.'], 422);
        }
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]); // This view will have the reset form
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);
       
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        // Attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));  // Fire the password reset event
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['status' => 'success', 'message' => 'Password has been reset successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => __($status)]);
        }
    }
}
