<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{

    public function index(Request $request)
    {
        $notifications = Notification::where('admin_id', Auth::guard('admin')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginated notifications

        return view('admin.notifications.index', compact('notifications'));
    }
    /**
     * Fetch all notifications for the logged-in admin.
     */
    public function fetch(Request $request)
    {
        $admin = Auth::guard('admin')->user(); // Use admin guard
        $notifications = Notification::where('user_id', $admin->id) // Replace with admin_id if applicable
            ->where('is_archived', false) // Exclude archived notifications
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate results

        return response()->json($notifications);
    }

    /**
     * Mark a single notification as read for admin.
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('id');

        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::guard('admin')->id()) // Use admin guard
            ->first();

        if ($notification) {
            $notification->is_read = true;
            $notification->save();
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }

    /**
     * Mark all notifications as read for admin.
     */
    public function markAllAsRead(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        Notification::where('user_id', $admin->id) // Replace with admin_id if applicable
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }

    /**
     * Archive a notification for admin.
     */
    public function archive(Request $request)
    {
        $notificationId = $request->input('id');

        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::guard('admin')->id()) // Use admin guard
            ->first();

        if ($notification) {
            $notification->is_archived = true;
            $notification->save();
            return response()->json(['success' => true, 'message' => 'Notification archived.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }

    /**
     * Search notifications by title or message for admin.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $admin = Auth::guard('admin')->user();

        $notifications = Notification::where('user_id', $admin->id) // Replace with admin_id if applicable
            ->where('is_archived', false)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('message', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }
}
