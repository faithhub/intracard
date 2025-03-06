<?php
namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Events\TicketCreated;
use App\Events\Trigger;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\TicketClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{

    public function fetchTickets()
    {
        $tickets = Ticket::with(['participants', 'closure'])
            ->where('created_by', Auth::id())
            ->orWhereHas('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tickets);
    }

    public function show($id)
    {
        $ticket = Ticket::with('closure')->findOrFail($id);
        return response()->json($ticket);
    }

    public function createTicket(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $ticket = Ticket::create([
            'created_by'  => Auth::id(),
            'subject'     => $request->subject ?: 'Untitled Ticket', // Default value
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        // Broadcast the ticket creation event
        broadcast(new TicketCreated($ticket))->toOthers();

        return response()->json($ticket, 201);
    }

    public function fetchMessages($ticketId)
    {
        $messages = Message::with('sender') // Include sender details
            ->where('ticket_id', $ticketId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id'          => $message->id,
                    'sender_id'   => $message->sender_id,
                    'sender_name' => $message->sender->name ?? 'Unknown', // Fallback for missing name
                    'role'        => $message->sender->role ?? 'User',    // Optional: Include sender's role
                    'message'     => $message->message,
                    'sender_type' => $message->sender_type,
                    'file_url'    => $message->file_path ? asset('storage/' . $message->file_path) : null,
                    'file_name'   => $message->file_name,
                    'created_at'  => $message->created_at->toDateTimeString(),
                ];
            });

        return response()->json($messages);
    }
    public function downloadFile(Message $message)
    {
        if (Auth::id() !== $message->sender_id && Auth::id() !== $message->receiver_id) {
            abort(403, 'Unauthorized access');
        }

        if (! $message->file_path || ! Storage::exists($message->file_path)) {
            abort(404, 'File not found');
        }

        // Decrypt the file contents
        $encryptedContents = Storage::get($message->file_path);
        $decryptedContents = decrypt($encryptedContents);

        // Return the decrypted file as a response
        return response($decryptedContents)
            ->header('Content-Type', mime_content_type(storage_path('app/' . $message->file_path)))
            ->header('Content-Disposition', 'attachment; filename="' . $message->file_name . '"');
    }

// Controller
    public function closeTicket(Request $request, $ticketId)
    {
        $request->validate([
            'status'   => 'required|in:resolved,unresolved',
            'rating'   => 'required|integer|between:1,5',
            'reason'   => 'required_if:status,unresolved|string|nullable',
            'feedback' => 'nullable|string',
        ], [
            'reason.required_if' => 'Please provide a reason when marking as unresolved',
            'rating.required'    => 'Please provide a rating',
            'rating.between'     => 'Rating must be between 1 and 5',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        if ($ticket->status !== 'pending') {
            return response()->json(['error' => 'Ticket is already closed.'], 400);
        }

        DB::transaction(function () use ($ticket, $request) {
            $ticket->update(['status' => $request->status]);

            TicketClosure::create([
                'ticket_id'         => $ticket->id,
                'closed_by'         => auth()->id(),
                'resolution_status' => $request->status,
                'reason'            => $request->reason,
                'feedback'          => $request->feedback,
                'rating'            => $request->rating,
            ]);
        });

        return response()->json(['message' => 'Ticket closed successfully']);
    }

    public function testChannel(){
        event(new Trigger("Hello, this is a test event!"));
        return response()->json(['status' => 'Event broadcasted']);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'message'   => 'nullable|string|max:1000',
            'file'      => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();

            // Encrypt file contents
            $encryptedContents = encrypt(file_get_contents($file->getRealPath()));
            $filePath          = 'ticket_files/' . uniqid() . '_' . $fileName;
            Storage::put($filePath, $encryptedContents);
        }

        $message = Message::create([
            'ticket_id'   => $request->ticket_id,
            'sender_id'   => Auth::id(),
            'sender_type' => 'user',
            'message'     => $request->message,
            'file_path'   => $filePath,
            'file_name'   => $fileName,
        ]);

        $this->testChannel();
        $message->load(['sender', 'ticket']);

        // broadcast(new MessageCreated($message))->toOthers();
        // Add error handling around the broadcast
        try {
            // If you have a socket ID from the request, use it to prevent echo
            $socketId = $request->header('X-Socket-ID');

            // Broadcast with socket ID if available
            if ($socketId) {
                broadcast(new MessageCreated($message))->toOthers();
                // broadcast(new MessageCreated($message))->toOthers();
            } else {
                broadcast(new MessageCreated($message));
            }
            // broadcast(new MessageCreated($message))->toOthers();
            \Log::info('Broadcast succeeded');
        } catch (\Exception $e) {
            \Log::error('Broadcast failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return response()->json($message, 201);
    }

    public function deleteTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        // Soft delete the ticket
        $ticket->delete();

        return response()->json(['message' => 'Ticket soft deleted.']);
    }

    public function restoreTicket($ticketId)
    {
        $ticket = Ticket::withTrashed()->findOrFail($ticketId);

        // Restore the ticket
        $ticket->restore();

        return response()->json(['message' => 'Ticket restored.']);
    }

    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);

        // Soft delete the message
        $message->delete();

        return response()->json(['message' => 'Message soft deleted.']);
    }

    public function restoreMessage($messageId)
    {
        $message = Message::withTrashed()->findOrFail($messageId);

        // Restore the message
        $message->restore();

        return response()->json(['message' => 'Message restored.']);
    }
}
