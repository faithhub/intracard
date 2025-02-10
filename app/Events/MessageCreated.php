<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('ticket.' . $this->message->ticket_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'ticket_id' => $this->message->ticket_id,
            'sender_id' => $this->message->sender_id,
            'message' => $this->message->message,
            'file_url' => $this->message->file_path ? route('files.download', $this->message->id) : null,
            'file_name' => $this->message->file_name,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
    
}

