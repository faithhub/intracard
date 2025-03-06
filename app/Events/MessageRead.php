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
    public $ticketUuid;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @param array $messageIds
     * @param int $conversationId
     */
    public function __construct(array $messageIds, string $ticketUuid, int $userId)
    {
        $this->messageIds = $messageIds;
        // $this->conversationId = $conversationId;
        $this->ticketUuid = $ticketUuid;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to the specific conversation channel
        // return new Channel('conversation.' . $this->conversationId);

        // Broadcast to the specific ticket channel
        return new Channel('ticket.' . $this->ticketUuid);
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
            // 'conversation_id' => $this->conversationId,
            'ticket_uuid' => $this->ticketUuid,
            'user_id' => $this->userId,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
