<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TicketUpdated implements ShouldBroadcast
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
        // Broadcast both to the specific ticket channel and the general tickets channel
        return [
            new Channel('ticket.' . $this->ticket->uuid),
            new Channel('tickets')
        ];
    }

    /**
     * Customize the broadcasted data.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->ticket->id,
            'uuid' => $this->ticket->uuid,
            'subject' => $this->ticket->subject,
            'status' => $this->ticket->status,
            'updated_at' => $this->ticket->updated_at->toDateTimeString(),
        ];
    }
}
