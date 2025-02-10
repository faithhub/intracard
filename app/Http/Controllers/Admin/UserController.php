<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            //code...
            $data['dashboard_title'] = "Admin Dashboard";

            $query = User::whereNull('deleted_at')->where('status', 'active');

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

            $data['users'] = $query->paginate(10);

            return view('admin.dashboard.users', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }

    public function viewUser($uuid)
    {
       try {
        //code...
        $data['dashboard_title'] = "Admin Dashboard";
        // Fetch the user by ID and ensure it is not soft-deleted
        $data['user'] = $user = User::where('uuid', $uuid)->whereNull('deleted_at')->firstOrFail();
        // $data['user'] = $user = User::where('id', $id)->whereNull('deleted_at')->first();

        if (!$user) {
            // Handle case where user is not found
            return redirect()->back()->with('error', 'User not found or has been deleted.');
        }

        // Return the user details to a Blade view
        return view('admin.dashboard.user-view', $data);
       } catch (\Throwable $th) {
        dd('');
        //throw $th;
       }
    }

}
