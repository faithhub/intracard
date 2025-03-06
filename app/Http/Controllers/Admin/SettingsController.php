<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoReply;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
     /**
     * Get all settings as a key-value array.
     */
    public function all_settings()
    {
        return Setting::pluck('value', 'key');
    }

    /**
     * Get the currently authenticated user.
     */
    public function get_active_user()
    {
        return User::findOrFail(Auth::user()->id);
    }
    
    /**
     * Display the settings page with all settings.
     */
    public function index()
    {
        // Fetch all settings
        $data['settings'] = Setting::where('is_show', true)->get();
        $data['autoReplies'] = AutoReply::where('status', true)->get();

        return view('admin.settings.index', $data);
    }

    /**
     * Store a new setting in the database.
     */
    public function store(Request $request)
    {
        // Log the request data for debugging
        \Log::info('Create setting request data:', $request->all());
        
        // Validate the input
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:settings,key',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:string,boolean,integer,json,file',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process value based on type
        $value = $request->value;
        
        // For boolean, ensure it's stored as '0' or '1'
        if ($request->type === 'boolean') {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        }
        
        // For JSON, validate it's valid JSON and handle it safely
        if ($request->type === 'json') {
            try {
                // Try to decode to verify it's valid
                $decoded = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON format');
                }
                
                // If it's already a string representation of JSON, use it
                // Otherwise, encode it to ensure it's stored properly
                if (is_array($decoded)) {
                    $value = json_encode($decoded);
                }
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid JSON format',
                        'errors' => ['value' => ['The value must be a valid JSON format.']]
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors(['value' => 'Invalid JSON format'])
                    ->withInput();
            }
        }

        // Create a new setting
        Setting::create([
            'key' => $request->key,
            'name' => $request->name,
            'value' => $value,
            'type' => $request->type,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Setting created successfully'
            ]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting created successfully');
    }

    /**
     * Update the specified setting in the database.
     * Now using POST method instead of PUT
     */
    public function update(Request $request, $id)
    {
        // Find the setting
        $setting = Setting::findOrFail($id);

        // Log the request data for debugging
        \Log::info('Update setting request data:', $request->all());
        
        // Validate the input based on the setting type
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process value based on type
        $value = $request->value;
        
        // For boolean, ensure it's stored as '0' or '1'
        if ($setting->type === 'boolean') {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        }
        
        // For JSON, validate it's valid JSON and handle it safely
        if ($setting->type === 'json') {
            try {
                // Try to decode to verify it's valid
                $decoded = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON format');
                }
                
                // If it's already a string representation of JSON, use it
                // Otherwise, encode it to ensure it's stored properly
                if (is_array($decoded)) {
                    $value = json_encode($decoded);
                }
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid JSON format',
                        'errors' => ['value' => ['The value must be a valid JSON format.']]
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors(['value' => 'Invalid JSON format'])
                    ->withInput();
            }
        }

        // Update the setting
        $setting->update([
            'name' => $request->name,
            'value' => $value,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully'
            ]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully');
    }

    /**
     * Soft delete a setting from the database.
     */
    public function destroy($id)
    {
        // Find and soft delete the setting
        $setting = Setting::findOrFail($id);
        $setting->delete(); // This will now use soft delete

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully'
            ]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully');
    }
    
    /**
     * Display a listing of the trashed settings.
     */
    public function trashed()
    {
        // Fetch soft deleted settings
        $data['settings'] = Setting::onlyTrashed()->get();
        
        return view('admin.settings.trashed', $data);
    }
    
    /**
     * Restore a soft-deleted setting.
     */
    public function restore($id)
    {
        // Find the soft deleted setting and restore it
        $setting = Setting::onlyTrashed()->findOrFail($id);
        $setting->restore();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Setting restored successfully'
            ]);
        }
        
        return redirect()->route('admin.settings.trashed')
            ->with('success', 'Setting restored successfully');
    }
    
    /**
     * Permanently delete a setting from the database.
     */
    public function forceDelete($id)
    {
        // Find and permanently delete the setting
        $setting = Setting::onlyTrashed()->findOrFail($id);
        $setting->forceDelete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Setting permanently deleted'
            ]);
        }
        
        return redirect()->route('admin.settings.trashed')
            ->with('success', 'Setting permanently deleted');
    }


    /**
 * Check if a setting key already exists
 * 
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */

 public function checkKeyExists(Request $request)
 {
     // Log the incoming request for debugging
     \Log::info('Checking key existence', ['key' => $request->input('key')]);
     
     try {
         $key = $request->input('key');
         
         if (empty($key)) {
             return response()->json([
                 'exists' => false,
                 'message' => 'No key provided'
             ]);
         }
         
         // Check both normal and trashed records
         $exists = Setting::where('key', $key)->exists() || 
                  Setting::onlyTrashed()->where('key', $key)->exists();
         
         // Log the result
         \Log::info('Key check result', [
             'key' => $key,
             'exists' => $exists
         ]);
         
         return response()->json([
             'exists' => $exists,
             'message' => $exists ? 'Key already exists' : 'Key is available'
         ]);
     } catch (\Exception $e) {
         // Log any errors
         \Log::error('Error checking key existence', [
             'key' => $request->input('key'),
             'error' => $e->getMessage()
         ]);
         
         return response()->json([
             'exists' => false,
             'message' => 'Error checking key',
             'error' => $e->getMessage()
         ], 500);
     }
 }
}