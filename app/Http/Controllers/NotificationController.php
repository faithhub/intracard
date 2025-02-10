<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginated notifications

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Fetch all notifications for the logged-in user.
     */
    public function fetchAll(Request $request)
    {
        $user = Auth::user();
        $category = $request->get('category', 'all');
        $readStatus = $request->get('status'); // 'read', 'unread', or null for all
    
        $query = Notification::where('user_id', $user->id)
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc');
    
        if ($category !== 'all') {
            $query->where('category', $category);
        }
    
        if ($readStatus === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($readStatus === 'unread') {
            $query->whereNull('read_at');
        }
    
        return response()->json([
            'notifications' => $query->paginate(20),
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }
    /**
     * Fetch all notifications for the logged-in user.
     */
    public function fetch2(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 5); // Default to 5 for dropdown, can be overridden
        $category = $request->get('category', 'all');
    
        // Base query for notifications
        $query = Notification::where('user_id', $user->id)
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc');
    
        // Apply category filter if specified
        if ($category !== 'all') {
            $query->where('category', $category);
        }
    
        // Get notifications with pagination
        $notifications = $query->take($limit)->get();
    
        // Get total counts using a single query for efficiency
        $counts = Notification::where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('is_read', false)
            ->selectRaw('
                COUNT(*) as total_unread,
                SUM(CASE WHEN category = "general" THEN 1 ELSE 0 END) as general_count,
                SUM(CASE WHEN category = "payment" THEN 1 ELSE 0 END) as payment_count,
                SUM(CASE WHEN category = "reminder" THEN 1 ELSE 0 END) as reminder_count
            ')
            ->first();
    
        // Get total count for current filter
        $totalCount = $query->count();
    
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $counts->total_unread,
            'category_counts' => [
                'general' => $counts->general_count,
                'payment' => $counts->payment_count,
                'reminder' => $counts->reminder_count,
            ],
            'has_more' => $totalCount > $limit,
            'total_count' => $totalCount
        ]);
    }

    public function fetch(Request $request)
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_archived', false) // Exclude archived notifications
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate results
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count(); // Count unread notifications

       
    // Count unread notifications by category
    $categoryCounts = [
        'general' => Notification::where('user_id', Auth::id())->where('is_read', false)->where('category', 'general')->count(),
        'payment' => Notification::where('user_id', Auth::id())->where('is_read', false)->where('category', 'payment')->count(),
        'reminder' => Notification::where('user_id', Auth::id())->where('is_read', false)->where('category', 'reminder')->count(),
    ];

    return response()->json([
        'notifications' => $notifications,
        'unread_count' => $unreadCount,
        'category_counts' => $categoryCounts, // Include category counts
    ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($notificationId)
    {
        // $notificationId = $request->input('id');

        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->is_read = true;
            $notification->save();
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }

    /**
     * Archive a notification.
     */
    public function archive(Request $request)
    {
        $notificationId = $request->input('id');

        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->is_archived = true;
            $notification->save();
            return response()->json(['success' => true, 'message' => 'Notification archived.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }

    /**
     * Search notifications by title or message.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_archived', false)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('message', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    /**
     * Delete a notification (optional).
     */
    public function delete(Request $request)
    {
        $notificationId = $request->input('id');

        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true, 'message' => 'Notification deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }
}
