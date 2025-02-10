<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoReply;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
     /**
     * Display a listing of the settings.
     */
    public function all_settings()
    {
        return Setting::pluck('value', 'key');
    }
    public function get_active_user()
    {
        return User::findOrFail(Auth::user()->id);
    }
    
    public function index()
    {
        // Fetch all settings
        $data['settings'] = Setting::all();
        $data['autoReplies'] = AutoReply::where('status', true)->get();

        return view('admin.settings.index', $data);
    }

    /**
     * Show the form for editing a specific setting.
     */
    public function edit($id)
    {
        // Find the setting by ID
        $setting = Setting::findOrFail($id);

        return view('settings.edit', compact('setting'));
    }

    /**
     * Update the specified setting in the database.
     */
    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'value' => 'required',
        ]);

        // Find the setting and update it
        $setting = Setting::findOrFail($id);
        $setting->update($request->only('value'));

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }

    /**
     * Store a new setting in the database.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'key' => 'required|unique:settings',
            'value' => 'required',
            'type' => 'required',
        ]);

        // Create a new setting
        Setting::create($request->only(['key', 'value', 'type']));

        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    /**
     * Delete a setting from the database.
     */
    public function destroy($id)
    {
        // Find and delete the setting
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully.');
    }
}
