<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Fetch OTP requirement from settings
        // $otpRequired = Setting::where('key', 'otp_enabled')->value('value') === 'true';

        // // Proceed if OTP is not required
        // if (!$otpRequired) {
        //     return $next($request);
        // }

        // // Check if the user is logged in
        // if (Auth::check()) {
        //     $user = Auth::user();

        //     // If the user is already verified
        //     if ($user->otp_verified) {
        //         // Prevent access to OTP verification page if already verified
        //         if ($request->routeIs('otp.verify') || $request->routeIs('otp.verify.submit')) {
        //             return redirect()->route('dashboard');
        //         }
        //     } else {
        //         // If OTP is required but not verified
        //         if (!$request->routeIs('otp.verify') && !$request->routeIs('otp.verify.submit')) {
        //             return redirect()->route('otp.verify')
        //                 ->with('message', 'Please verify your OTP to access this page.');
        //         }
        //     }
        // }

        return $next($request);
    }
}
