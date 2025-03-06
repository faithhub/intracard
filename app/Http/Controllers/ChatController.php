<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
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
            $data['dashboard_title'] = "Chat Us";
            return view('user.chat-us', $data);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
    public function sendMessage2(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:10240', // 10MB max file size
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            // Read file contents and encrypt
            $encryptedContents = encrypt(file_get_contents($file->getRealPath()));

            // Save encrypted file
            $filePath = 'chat_files/' . uniqid() . '_' . $fileName;
            Storage::put($filePath, $encryptedContents);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        // Check for auto-replies
        if ($request->message) {
            $this->checkAutoReplies($request->message, $request->receiver_id);
        }

        broadcast(new MessageCreated($message))->toOthers();

        return response()->json($message, 201);
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
            'message' => $request->message,
            'sender_type' => 'user',
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
        $userId = Auth::id();
    
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
        ]);
    
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
    
        return response()->json($messages);
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
        if (Auth::id() !== $message->sender_id && Auth::id() !== $message->receiver_id) {
            abort(403, 'Unauthorized access');
        }

        if (!$message->file_path || !Storage::exists($message->file_path)) {
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

}
