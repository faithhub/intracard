<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Display a listing of the admin users
    public function index(Request $request)
    {
        try {
            // Start the query to retrieve admin data with eager loading for roles
            $query = Admin::with('roles'); // Eager load roles to avoid N+1 queries
    
            // Apply filters based on request parameters
            if ($request->filled('role') && $request->role !== 'all') {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('slug', $request->role);  // Use 'slug' for role filtering
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
            $admins = $query->get();
    
            $data['admins'] = $admins->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'first_name' => $admin->first_name,
                    'last_name' => $admin->last_name,
                    'email' => $admin->email,
                    'phone' => $this->formatPhoneNumber($admin->phone),
                    'status' => $admin->status,
                    'created_at' => $admin->created_at->format('d M Y, h:i A'),
                    'roles' => $admin->roles->map(function ($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                            'slug' => $role->slug,  // Include the slug
                            'description' => $role->description,  // Include the description
                            'display_name' => ucwords(str_replace('_', ' ', $role->name)),
                        ];
                    }),
                    'primary_role' => $admin->roles->first() ? ucwords(str_replace('_', ' ', $admin->roles->first()->name)) : 'No Role',
                ];
            });
    
            // Get all roles for filter dropdown
            $data['roles'] = Role::all()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'slug' => $role->slug,  // Include the slug for filtering
                    'display_name' => ucwords(str_replace('_', ' ', $role->name)),
                ];
            });
    
            // Pass filters back to the view
            $data['filters'] = $request->all();
    
            return view('admin.admin.index', $data);
    
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            dd($e->getMessage());
            \Log::error("Error fetching admin data: " . $e->getMessage());
    
            // Optionally, you can return an error message to the view or a default fallback
            return redirect()->route('admin.dashboard')->with('error', 'An error occurred while fetching admin data. Please try again later.');
        }
    }
    

    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return 'Not provided';
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Format as Canadian phone number: (XXX) XXX-XXXX
        if (strlen($phone) == 10) {
            return sprintf("(%s) %s-%s",
                substr($phone, 0, 3),
                substr($phone, 3, 3),
                substr($phone, 6)
            );
        }

        return $phone;
    }

    // Store a newly created admin user in the database
    public function show($id)
{
    try {
        // Fetch the admin details
        $admin = Admin::with('roles')->findOrFail($id);
        
        // Format the phone number
        $admin->phone = $this->formatPhoneNumber($admin->phone);

        // Pass the formatted admin data to the view
        return view('admin.admin.show_modal', compact('admin'));
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to load admin details',
            'message' => $e->getMessage()
        ], 500);
    }
}

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

    public function edit($id)
    {
        $admin = Admin::with('roles')->findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $admin = Admin::findOrFail($id);
    
        // Validate the incoming data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:admins,email,' . $id,
            'phone'      => 'nullable|string|max:255',
            'status'      => 'nullable|string|max:255',
            'roles'      => 'required|array',  // Roles should be an array
        ]);
    
        // Update the admin's basic details
        $admin->update($validated);
    
        // Update the admin's roles
        if ($request->has('roles')) {
            $admin->roles()->sync($request->roles);
        }
    
        // Return a JSON response to inform the front-end
        return response()->json([
            'success' => true,
            'message' => 'Admin updated successfully',
            'data'    => $admin // Return the updated admin object if needed
        ]);
    }
    
    
    // Update the specified admin user in the database
    public function update2(Request $request, Admin $admin)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:admins,email,' . $admin->id,
        ]);

        $admin->update([
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'middle_name' => $request->middle_name,
            'phone'       => $request->phone,
            'email'       => $request->email,
            'password'    => $request->password ? Hash::make($request->password) : $admin->password,
        ]);

        return redirect()->route('admin-users.index')->with('success', 'Admin user updated successfully.');
    }

    // Remove the specified admin user from the database
    public function destroy($id)
    {
        try {
            // Delete the admin
            $admin = Admin::findOrFail($id);
            $admin->delete();
    
            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'message' => 'Admin user deleted successfully.'
            ]);
        } catch (\Exception $e) {
            // Handle any errors that might occur during the delete process
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete admin user: ' . $e->getMessage()
            ], 500);  // Return 500 status code for server errors
        }
    }
    
}
