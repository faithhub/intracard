<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // return $request->all();
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user(),
                'redirect_url' => '/dashboard',
            ]);
        }
    
        return response()->json([
            'authenticated' => false,
            'message' => 'Invalid credentials',
        ], 401); // Return 401 for invalid credentials
    }
    

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully']);
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
}
