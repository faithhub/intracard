<?php
namespace App\Events;

use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;

        // Ensure ticket is loaded
        if (! $message->relationLoaded('ticket')) {
            $message->load('ticket');
        }
        
        // Debug log
        // \Log::info('MessageCreated event firing', [
        //     'message_id'  => $message->id,
        //     'ticket_uuid' => $message->ticket->uuid,
        //     'channel'     => 'ticket.' . $message->ticket->uuid,
        //     'sender_type' => $message->sender_type,
        // ]);
    }

    // Important - NO "." prefix in the event name
    public function broadcastAs()
    {
        return 'MessageCreated';
    }

    public function broadcastOn()
    {
        // Make sure you use the ticket's UUID, not the ID
        return new Channel('ticket.' . $this->message->ticket->uuid);
    }

    public function broadcastWith()
    {
        return [
            'id'          => $this->message->id,
            'ticket_id'   => $this->message->ticket_id,
            'ticket_uuid' => $this->message->ticket->uuid,
            'sender_id'   => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'message'     => $this->message->message,
            'file_path'   => $this->message->file_path,
            'file_name'   => $this->message->file_name,
            'has_file'    => ! is_null($this->message->file_path),
            'created_at'  => $this->message->created_at->toDateTimeString(),
            'sender'      => $this->message->sender,
        ];
    }

}
