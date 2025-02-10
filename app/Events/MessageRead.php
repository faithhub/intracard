<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageRead implements ShouldBroadcastNow
{
    use SerializesModels;

    public $messageIds;
    public $conversationId;

    /**
     * Create a new event instance.
     *
     * @param array $messageIds
     * @param int $conversationId
     */
    public function __construct(array $messageIds, $conversationId)
    {
        $this->messageIds = $messageIds;
        $this->conversationId = $conversationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to the specific conversation channel
        return new Channel('conversation.' . $this->conversationId);
    }

    /**
     * Data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message_ids' => $this->messageIds,
            'conversation_id' => $this->conversationId,
        ];
    }
}
