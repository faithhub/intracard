<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Display a listing of the admin users
    public function index(Request $request)
    {
        $query = Admin::query(); // Start a query builder for Admins
    
        // Apply filters based on request parameters
        if ($request->filled('role') && $request->role !== 'all') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
    
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('specific_date')) {
            $query->whereDate('created_at', $request->specific_date);
        }
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
    
        // Perform search if search query is provided
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
    
        // Get filtered admin data
        $data['admins'] = $query->get();
        $data['roles'] = Role::all(); // Assuming you have a Role model
    
        // Pass filters back to the view for preserving selected values
        $data['filters'] = $request->all();
    
        return view('admin.admin.index', $data);
    }
    

    // Show the form for creating a new admin user
    public function create()
    {
        return view('admins.create');
    }

    // Store a newly created admin user in the database
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|min:8',
                'role' => 'required|exists:roles,id',
            ]);

            // Create admin
            $admin = Admin::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $request->middle_name,
                'phone' => $request->phone,
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            // Assign role
            $admin->roles()->attach($validated['role']);

            // Return success response
            return response()->json(['success' => true, 'message' => 'Admin created successfully!', 'admin' => $admin], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    // Display the specified admin user
    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    // Show the form for editing the specified admin user
    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    // Update the specified admin user in the database
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
        ]);

        $admin->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $admin->password,
        ]);

        return redirect()->route('admin-users.index')->with('success', 'Admin user updated successfully.');
    }

    // Remove the specified admin user from the database
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin-users.index')->with('success', 'Admin user deleted successfully.');
    }
}
