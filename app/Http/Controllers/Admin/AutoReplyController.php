<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoReply;
use Illuminate\Http\Request;

class AutoReplyController extends Controller
{
    public function index()
    {
        $autoReplies = AutoReply::where('status', true)->get();
        return view('admin.settings.index', compact('autoReplies'));
    }

    public function create()
    {
        return view('admin.auto_replies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keywords' => 'required|array',
            'response' => 'required|string',
        ]);

        AutoReply::create([
            'keywords' => $request->keywords,
            'response' => $request->response,
        ]);

        return back()->with('success', 'Auto-reply created successfully.');
    }
    

    public function edit(AutoReply $autoReply)
    {
        // Find the setting by ID
        return view('admin.auto_replies.edit', compact('autoReply'));
    }

    public function update(Request $request, AutoReply $autoReply)
    {
        $request->validate([
            'keywords' => 'required|array',
            'response' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $autoReply->update([
            'keywords' => $request->keywords,
            'response' => $request->response,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Auto-reply updated successfully.');
    }

    public function destroy(AutoReply $autoReply)
    {
        $autoReply->delete();

        return back()->with('success', 'Auto-reply deleted successfully.');
    }
}