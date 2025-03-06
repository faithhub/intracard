<?php
namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Change this to your admin dashboard route
    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    // Use admin guard for authentication
    protected function guard()
    {
        return Auth::guard('admin');
    }

    // Override the username method if you're not using email
    public function username()
    {
        return 'email'; // or 'username' if that's what you use
    }

    protected function authenticated(Request $request, $user)
    {
        // Check if there's an intended URL stored in the session
        if ($request->session()->has('admin_intended_url')) {
            $url = $request->session()->pull('admin_intended_url');
            return redirect()->to($url);
        }

        return redirect()->intended($this->redirectPath());
    }
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
            'email' => 'required|email|max:255',
            'password'  => 'required|string|max:20',
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors'  => $validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // Retrieve admin by email
            $admin = Admin::where('email', $request->email)->first();

            // Check if admin exists and password matches
            if (! $admin || ! Hash::check($request->password, $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials.',
                ], 401); // 401 Unauthorized
            }

            // Optional: Ensure admin is active (if your `admins` table has an `is_active` field)
            if (isset($admin->status) && $admin->status != 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is inactive. Please contact support.',
                ], 403); // 403 Forbidden
            }

            $redirectUrl = null;
            if ($request->session()->has('admin_intended_url')) {
                $redirectUrl = $request->session()->pull('admin_intended_url');
            }

            // dd($admin);

            // Log in the admin
            Auth::guard('admin')->login($admin);

            // Return successful login response
            return response()->json([
                'success'      => true,
                'message'      => 'Login successful!',
                'redirect_url' => $redirectUrl ?? route('admin.dashboard'),
            ], 200);

        } catch (\Throwable $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. ' . $e->getMessage(),
                'error'   => $e->getMessage(), // Optionally include error for debugging
            ], 500);                       // 500 Internal Server Error
        }
    }

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
                    'otp_verified'   => false,
                    'otp_code'       => null,
                    'otp_expires_at' => null,
                ]);
            }

            // Return response for successful logout
            return response()->json([
                'success'      => true,
                'message'      => 'You have been successfully logged out.',
                'redirect_url' => route('admin.login'),
            ], 200);
        }

        // If no admin is authenticated, return an error response
        return response()->json([
            'success' => false,
            'message' => 'No admin is currently logged in.',
        ], 401);
    }

}
