<?php
namespace App\Http\Controllers\Admin;

use App\Events\GotMessage;
use App\Events\MessageRead;
use App\Events\NotificationEvent;
use App\Events\TicketUpdated;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// use MyEvent;

class AdminSupportController extends Controller
{

    /**
     * Display the support dashboard
     */
    public function index()
    {
        return view('admin.support.index');
    }

    /**
     * Get all tickets for the admin
     */
    public function getTickets(Request $request)
    {
        $query = Ticket::with(['creator:id,first_name,last_name,email'])
            ->select('id', 'uuid', 'created_by', 'subject', 'status', 'created_at');

        // Apply search filter if provided
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Apply status filter if provided
        if ($request->has('status') && ! empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sort by latest by default
        $tickets = $query->latest()->paginate(10);

        // Add unread message count to each ticket
        foreach ($tickets as $ticket) {
            $ticket->unread_count = $this->getUnreadCount($ticket->id);

            // Get the latest message date
            $latestMessage = Message::where('ticket_id', $ticket->id)
                ->latest()
                ->first();

            $ticket->last_activity = $latestMessage ? $latestMessage->created_at : $ticket->created_at;
        }

        return response()->json($tickets);
    }

    /**
     * Get a specific ticket with its messages
     */
    public function getTicket($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)
            ->with(['creator:id,first_name,last_name,email'])
            ->firstOrFail();

        return response()->json($ticket);
    }

    /**
     * Get messages for a specific ticket
     */
    public function getMessages($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();

        $messages = Message::where('ticket_id', $ticket->id)
            ->with('sender:id,first_name,last_name,email')
            ->orderBy('created_at')
            ->get();

        // Mark messages as read
        $this->markMessagesAsRead($ticket->id);

        return response()->json($messages);
    }

    /**
     * Send a message from admin
     */
    public function sendMessage(Request $request, $uuid)
    {
        $request->validate([
            'message' => 'required_without:file|nullable|string',
            'file'    => 'nullable|file|max:10240', // 10MB max
        ]);

        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
        $admin  = Auth::guard('admin')->user();

        $message              = new Message();
        $message->ticket_id   = $ticket->id;
        $message->sender_id   = $admin->id;
        $message->sender_type = 'admin';
        $message->message     = $request->message;

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();

            // Encrypt file contents
            $encryptedContents = encrypt(file_get_contents($file->getRealPath()));
            $filePath          = 'chat_files/' . uniqid() . '_' . $fileName;
            Storage::put($filePath, $encryptedContents);

            $message->file_path = $filePath;
            $message->file_name = $fileName;
        }

        $message->save();

        $message->load(['sender', 'ticket']);

        // If you have a socket ID from the request, use it to prevent echo
        // $socketId = $request->header('X-Socket-ID');

        GotMessage::dispatch(
            [
                'id'          => $message->id,
                'ticket_id'   => $message->ticket_id,
                'sender_id'   => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender'      => $message->sender,
                'ticket'      => $message->ticket,
            ]
        );
        // Broadcast with socket ID if available
        // event(new \App\Events\MessageCreated($message));
        // if ($socketId) {
        //     broadcast(new MessageCreated($message))->toOthers();
        // } else {
        //     broadcast(new MessageCreated($message));
        // }

        // Use the existing MessageCreated event to broadcast
        // broadcast(new MessageCreated($message))->toOthers();

        // Return the message with sender info for immediate display
        return response()->json([
            'message' => [
                'id'          => $message->id,
                'ticket_id'   => $message->ticket_id,
                'sender_id'   => $message->sender_id,
                'sender_type' => $message->sender_type, // Include this
                'message'     => $message->message,
                'file_path'   => $message->file_path,
                'file_name'   => $message->file_name,
                'avatar'      => $admin->avatar ?? null,
                'created_at'  => $message->created_at,
            ],
            'sender'  => [
                'id'         => $admin->id,
                'first_name' => $admin->first_name,
                'last_name'  => $admin->last_name,
                'email'      => $admin->email,
            ],
        ], 201);
    }

    /**
     * Download a file attached to a message
     */
    public function downloadFile($id)
    {
        $message = Message::findOrFail($id);

        if (! $message->file_path) {
            abort(404, 'File not found');
        }

        return Storage::disk('private')->download($message->file_path, $message->file_name);
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $uuid)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,resolved,unresolved',
        ]);

        $ticket         = Ticket::where('uuid', $uuid)->firstOrFail();
        $ticket->status = $validated['status'];
        $ticket->save();

        // Broadcast the status change
        broadcast(new TicketUpdated($ticket))->toOthers();

        return response()->json(['message' => 'Ticket status updated successfully']);
    }

    /**
     * Close a ticket
     */
    public function closeTicket(Request $request, $uuid)
    {
        $validated = $request->validate([
            'resolution_status' => 'required|in:resolved,unresolved',
            'reason'            => 'required|string',
        ]);

        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
        $admin  = Auth::guard('admin')->user();

        // Update ticket status
        $ticket->status = $validated['resolution_status'];
        $ticket->save();

        // Create ticket closure
        $closure = $ticket->closure()->create([
            'closed_by'         => $admin->id,
            'resolution_status' => $validated['resolution_status'],
            'reason'            => $validated['reason'],
        ]);

        // Broadcast ticket status change
        broadcast(new TicketUpdated($ticket))->toOthers();

        // Optionally send notification to the user
        broadcast(new NotificationEvent(
            'Ticket Closed',
            "Your ticket '{$ticket->subject}' has been closed."
        ))->toOthers();

        return response()->json(['message' => 'Ticket closed successfully']);
    }

    /**
     * Get unread message count for dashboard
     */
    public function getUnreadMessageCount()
    {
        $counts = [
            'total'   => 0,
            'tickets' => [],
        ];

        $tickets = Ticket::all();

        foreach ($tickets as $ticket) {
            $unreadCount = $this->getUnreadCount($ticket->id);
            if ($unreadCount > 0) {
                $counts['tickets'][$ticket->uuid] = $unreadCount;
                $counts['total'] += $unreadCount;
            }
        }

        return response()->json($counts);
    }

    /**
     * Get the count of unread messages for a ticket
     */
    private function getUnreadCount($ticketId)
    {
        $adminId = Auth::guard('admin')->id();

        return Message::where('ticket_id', $ticketId)
            ->where('sender_id', '!=', $adminId) // Messages not sent by this admin
            ->whereNull('read_at')               // Unread messages
            ->count();
    }

    /**
     * Mark messages as read for a ticket
     */
    private function markMessagesAsRead($ticketId)
    {
        $adminId = Auth::guard('admin')->id();

        Message::where('ticket_id', $ticketId)
            ->where('sender_id', '!=', $adminId) // Messages not sent by this admin
            ->whereNull('read_at')               // Only unread messages
            ->update(['read_at' => now()]);
    }

/**
 * Mark specific messages as read
 */
    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'ticket_uuid'   => 'required|exists:tickets,uuid',
            'message_ids'   => 'required|array',
            'message_ids.*' => 'exists:messages,id',
        ]);

        $ticket = Ticket::where('uuid', $validated['ticket_uuid'])->firstOrFail();
        $admin  = Auth::guard('admin')->user();

        // Update read_at for these messages
        Message::whereIn('id', $validated['message_ids'])
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Broadcast that these messages have been read
        broadcast(new MessageRead(
            $validated['message_ids'],
            $validated['ticket_uuid'],
            $admin->id
        ))->toOthers();

        return response()->json(['success' => true]);
    }
}
