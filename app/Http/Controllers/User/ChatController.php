<?php

namespace App\Http\Controllers\User;

use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AutoReply;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $data['userData'] = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ];

            $data['dashboard_title'] = "Chat Us";
            return view('user.chat-us', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function startConversation()
    {
        $creatorId = Auth::id(); // The current authenticated user
    
        // Determine the recipient dynamically (e.g., assign to default admin)
        $recipientId = $this->getDefaultRecipientId();
    
        // Check if a conversation exists between the current user and the recipient
        $conversation = Conversation::where(function ($query) use ($creatorId, $recipientId) {
            $query->where('created_by', $creatorId)
                  ->orWhere('created_by', $recipientId); // Check for any existing conversation
        })->first();
    
        if (!$conversation) {
            // Create a new conversation if none exists
            $conversation = Conversation::create([
                'created_by' => $creatorId,
            ]);
        }
    
        return response()->json([
            'conversation_id' => $conversation->id,
        ]);
    }
    
    /**
     * Get the default recipient for the conversation.
     * Replace with your logic to assign a recipient (e.g., assign to admin or support).
     */
    private function getDefaultRecipientId()
    {
        // Example: Assign to the first admin user
        return Admin::first()->id;
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            // Encrypt file contents
            $encryptedContents = encrypt(file_get_contents($file->getRealPath()));
            $filePath = 'chat_files/' . uniqid() . '_' . $fileName;
            Storage::put($filePath, $encryptedContents);
        }

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => Auth::id(),
            'sender_type' => $request->sender_type,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        broadcast(new MessageCreated($message))->toOthers();

        return response()->json($message, 201);
    }

    private function checkAutoReplies($message, $receiverId)
    {
        // Fetch only enabled auto-replies
        $autoReplies = AutoReply::where('status', true)->get();

        foreach ($autoReplies as $autoReply) {
            // Check if the message contains any of the keywords
            foreach ($autoReply->keywords as $keyword) {
                if (stripos($message, $keyword) !== false) {
                    // Save the auto-reply message
                    Message::create([
                        'sender_id' => 1, // Assuming admin ID is 1
                        'receiver_id' => $receiverId,
                        'message' => $autoReply->response,
                    ]);

                    return; // Send only one auto-reply
                }
            }
        }
    }

    public function fetchMessages(Request $request)
    {

        try {
            $userId = Auth::id();

            // Check if the user has any existing conversations
            $conversationId = $request->conversation_id;
            if (!$conversationId) {
                $conversation = Conversation::whereHas('participants', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->first();

                if ($conversation) {
                    $conversationId = $conversation->id;
                } else {
                    // No conversation exists; return an empty response
                    return response()->json([], 200);
                }
            }

            dd($conversationId);
            $messages = Message::where('conversation_id', $request->conversation_id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'message' => $message->message,
                        'file_url' => $message->file_path ? route('files.download', $message->id) : null,
                        'file_name' => $message->file_name,
                        'created_at' => $message->created_at->toDateTimeString(),
                        'is_read' => $message->read_at !== null,
                    ];
                });

            return response()->json($messages, 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error('Error fetching messages', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch messages'], 500);
        }
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id',
        ]);

        Message::whereIn('id', $request->message_ids)
            ->where('receiver_id', Auth::id()) // Ensure the current user is the receiver
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Messages marked as read'], 200);
    }

    public function downloadFile(Message $message)
    {
        // Authorization check
        if (Auth::id() !== $message->sender_id && Auth::id() !== $message->receiver_id) {
            abort(403, 'Unauthorized access');
        }

        // Full file path in the storage folder
        $filePath = storage_path('app/' . $message->file_path);

        // Check if the file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Decrypt the file contents
        $encryptedContents = Storage::get($message->file_path);
        $decryptedContents = decrypt($encryptedContents);

        // Determine the MIME type
        $mimeType = mime_content_type($filePath);

        // Return the decrypted file as a response
        return response($decryptedContents)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'attachment; filename="' . $message->file_name . '"');
    }

}
