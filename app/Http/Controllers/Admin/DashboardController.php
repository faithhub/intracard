<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            //code...
            //             $appId = random_int(100_000, 999_999);
            // $appKey = Str::lower(Str::random(20));
            // $appSecret = Str::lower(Str::random(20));

            // dd($appId, $appKey, $appSecret);
            $data['dashboard_title'] = "Admin Dashboard";
            $data['totalUsers'] = User::count();
            $data['activeUsers'] = User::where('status', 'active')->count();
            $data['rentUsers'] = User::where('account_type', 'rent')->count();
            $data['mortgageUsers'] = User::where('account_type', 'mortgage')->count();
            //  $data['pendingRequests'] = DB::table('requests')->where('status', 'Pending')->count(); // Example
            //  $data['monthlyEarnings'] = DB::table('transactions')
            //     ->whereMonth('created_at', now()->month)
            //     ->sum('amount');

            $data['recentActivities'] = [
                // ['description' => 'User John Doe registered.', 'time' => '2 hours ago'],
                // ['description' => 'Admin approved a request.', 'time' => '1 day ago'],
            ];
            return view('admin.dashboard.index', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
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
            $query = User::where('status', 'pending');

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
}
