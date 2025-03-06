<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillHistory;
use App\Models\CardTransaction;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    
    public function index()
    {
        try {
            $data['dashboard_title'] = "Admin Dashboard";
            $data['totalUsers'] = User::count();
            $data['activeUsers'] = User::where('status', 'active')->count();
            $data['rentUsers'] = User::where('account_goal', 'rent')->count();
            $data['mortgageUsers'] = User::where('account_goal', 'mortgage')->count();
            
            // Calculate monthly earnings from card and wallet transactions
            $currentMonth = Carbon::now()->startOfMonth();
            $cardEarnings = CardTransaction::whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentMonth->year)
                ->where('status', 'completed')
                ->sum('amount');
                
            $walletEarnings = WalletTransaction::whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentMonth->year)
                ->where('status', 'completed')
                ->sum('amount');
                
            $data['monthlyEarnings'] = $cardEarnings + $walletEarnings;
            
            // Get recent activities
            $data['recentActivities'] = $this->getDashboardActivities();
            
            // Add data for charts
            $data['userRegistrationData'] = $this->getUserRegistrationData();
            $data['transactionData'] = $this->getTransactionData();
            $data['revenueData'] = $this->getRevenueData();
            $data['userDistributionData'] = $this->getUserDistributionData();

            // dd($data);
            
            return view('admin.dashboard.index', $data);
        } catch (\Throwable $th) {
            // Log error or handle appropriately
            return view('admin.dashboard.index')->with('error', 'An error occurred while loading dashboard data.');
        }
    }

    public function getUserRegistrationData()
    {
        // Get user registrations for the past 6 months
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $registrations = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<=', $endDate)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        $formattedData = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            $monthName = $currentDate->format('M');
            
            $count = 0;
            foreach ($registrations as $registration) {
                if ($registration->year == $year && $registration->month == $month) {
                    $count = $registration->count;
                    break;
                }
            }
            
            $formattedData[] = [
                'month' => $monthName,
                'users' => $count
            ];
            
            $currentDate->addMonth();
        }
        
        return $formattedData;
    }
    
    public function getTransactionData()
    {
        // Get transactions for the past 6 months by type
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Card Transactions
        $cardTransactions = CardTransaction::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<=', $endDate)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        // Wallet Transactions
        $walletTransactions = WalletTransaction::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<=', $endDate)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        $formattedData = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            $monthName = $currentDate->format('M');
            
            $cardCount = 0;
            foreach ($cardTransactions as $transaction) {
                if ($transaction->year == $year && $transaction->month == $month) {
                    $cardCount = $transaction->count;
                    break;
                }
            }
            
            $walletCount = 0;
            foreach ($walletTransactions as $transaction) {
                if ($transaction->year == $year && $transaction->month == $month) {
                    $walletCount = $transaction->count;
                    break;
                }
            }
            
            $formattedData[] = [
                'month' => $monthName,
                'card' => $cardCount,
                'wallet' => $walletCount
            ];
            
            $currentDate->addMonth();
        }
        
        return $formattedData;
    }
    
    public function getRevenueData()
    {
        // Get revenue for the past 6 months
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Card Revenue
        $cardRevenues = CardTransaction::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total')
        )
        ->where('status', 'completed')
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<=', $endDate)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        // Wallet Revenue
        $walletRevenues = WalletTransaction::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total')
        )
        ->where('status', 'completed')
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<=', $endDate)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        $formattedData = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            $monthName = $currentDate->format('M');
            
            $cardTotal = 0;
            foreach ($cardRevenues as $revenue) {
                if ($revenue->year == $year && $revenue->month == $month) {
                    $cardTotal = $revenue->total;
                    break;
                }
            }
            
            $walletTotal = 0;
            foreach ($walletRevenues as $revenue) {
                if ($revenue->year == $year && $revenue->month == $month) {
                    $walletTotal = $revenue->total;
                    break;
                }
            }
            
            $formattedData[] = [
                'month' => $monthName,
                'card' => $cardTotal,
                'wallet' => $walletTotal,
                'total' => $cardTotal + $walletTotal
            ];
            
            $currentDate->addMonth();
        }
        
        return $formattedData;
    }
    
    public function getUserDistributionData()
    {
        $totalUsers = User::count();
        $rentUsers = User::where('account_goal', 'rent')->count();
        $mortgageUsers = User::where('account_goal', 'mortgage')->count();
        $otherUsers = $totalUsers - $rentUsers - $mortgageUsers;
        
        return [
            ['type' => 'Rent', 'count' => $rentUsers],
            ['type' => 'Mortgage', 'count' => $mortgageUsers],
            ['type' => 'Other', 'count' => $otherUsers]
        ];
    }

    public function getDashboardActivities()
    {
        // First, get the activity data as before
        $users = DB::table('users')
            ->select(
                DB::raw('"user" as type'),
                'id',
                'first_name',
                'last_name',
                'email',
                'created_at',
                DB::raw('"active" as activity_status'),
                DB::raw('NULL as amount'),
                DB::raw('id as user_id')
            )
            ->whereNull('deleted_at');
    
        $cardTransactions = DB::table('card_transactions')
            ->select(
                DB::raw('"card_transaction" as type'),
                'id',
                DB::raw('NULL as first_name'),
                DB::raw('NULL as last_name'),
                DB::raw('NULL as email'),
                'created_at',
                'status as activity_status',
                'amount',
                'user_id'
            );
    
        $walletTransactions = DB::table('wallet_transactions')
            ->select(
                DB::raw('"wallet_transaction" as type'),
                'id',
                DB::raw('NULL as first_name'),
                DB::raw('NULL as last_name'),
                DB::raw('NULL as email'),
                'created_at',
                'status as activity_status',
                'amount',
                'user_id'
            );
    
        $billPayments = DB::table('bill_histories')
            ->select(
                DB::raw('"bill_payment" as type'),
                'id',
                DB::raw('NULL as first_name'),
                DB::raw('NULL as last_name'),
                DB::raw('NULL as email'),
                'created_at',
                'status as activity_status',
                'amount',
                'user_id'
            );
        
        $activities = $users
            ->unionAll($cardTransactions)
            ->unionAll($walletTransactions)
            ->unionAll($billPayments)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        // Now, for each activity, use the Eloquent model to get decrypted user data
        $activities = collect($activities)->map(function ($activity) {
            // Convert stdClass to array for easier manipulation
            $activityArray = (array) $activity;
            
            // For non-user activities, fetch the user model to get decrypted names
            if ($activity->type !== 'user') {
                // Use Eloquent to get automatically decrypted values
                $user = User::find($activity->user_id);
                
                if ($user) {
                    $activityArray['first_name'] = $user->first_name;
                    $activityArray['last_name'] = $user->last_name;
                    $activityArray['email'] = $user->email;
                } else {
                    $activityArray['first_name'] = 'N/A';
                    $activityArray['last_name'] = 'N/A';
                    $activityArray['email'] = 'N/A';
                }
            } else {
                // For user-type activities, decode the encrypted values
                $user = User::find($activity->id);
                if ($user) {
                    $activityArray['first_name'] = $user->first_name;
                    $activityArray['last_name'] = $user->last_name;
                    $activityArray['email'] = $user->email;
                }
            }
            
            // Create full name
            $activityArray['user_name'] = trim(
                ($activityArray['first_name'] ?? '') . ' ' . 
                ($activityArray['last_name'] ?? '')
            );
            
            return (object) $activityArray;
        });
    
        return $activities;
    }

    public function test()
    {
        try {
            //code...
            $data['dashboard_title'] = "Admin Dashboard";
            return view('admin.dashboard.test', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function help()
    {
        try {
            //code...
            $data['dashboard_title'] = "Admin Dashboard";
            return view('admin.dashboard.help', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function onboarding(Request $request)
    {
        try {
            //code...
            $data['dashboard_title'] = "Admin Dashboard";
            // Fetch all users with status 'pending'
            // Initialize query for pending users
            $query = User::where('status', 'pending')->orderBy('created_at', 'desc');

            // Apply account goal filter
            if ($request->filled('account_goal') && $request->account_goal !== 'all') {
                $query->where('account_goal', $request->account_goal);
            }

            // Apply account type filter
            if ($request->filled('account_type') && $request->account_type !== 'all') {
                $query->where('account_type', $request->account_type);
            }

            // Apply payment setup filter
            if ($request->filled('payment_setup') && $request->payment_setup !== 'all') {
                $query->where('payment_setup', $request->payment_setup);
            }

            // Apply specific date filter
            if ($request->filled('specific_date')) {
                $query->whereDate('created_at', $request->specific_date);
            }

            // Apply date range filter
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            $data['pendingUsers'] = $query->paginate(10);

            return view('admin.dashboard.onboarding', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function profile()
    {
        return view('admin.settings.profile');
    }
    
    /**
     * Update the admin profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s]*$/',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s]*$/',
            'phone' => 'nullable|string|min:10|max:15|regex:/^[0-9+\-\s()]*$/',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $user = Auth::guard('admin')->user();
            
            // Update user profile
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            
            // Handle profile picture upload if provided
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                
                // Remove old profile picture if exists and not the default
                if ($user->profile_picture && !str_contains($user->profile_picture, 'default-avatar')) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                
                // Store the new image
                $path = $file->store('profile-pictures', 'public');
                $user->profile_picture = $path;
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'profile_picture' => $user->profile_picture ? Storage::url($user->profile_picture) : null
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update the admin password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ],
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $user = Auth::guard('admin')->user();
            
            // Check if current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 422);
            }
            
            // Update password
            $user->password = Hash::make($request->password);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $request->all(),
                'message' => 'Failed to change password: ' . $e->getMessage()
            ], 500);
        }
    }
}
