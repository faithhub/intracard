<?php
namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TicketCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $ticket;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('tickets');
    }

    /**
     * Customize the broadcasted data.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->ticket->id,
            'subject' => $this->ticket->subject,
            'description' => $this->ticket->description,
            'created_by' => $this->ticket->created_by,
            'status' => $this->ticket->status,
            'created_at' => $this->ticket->created_at->toDateTimeString(),
        ];
    }
}
