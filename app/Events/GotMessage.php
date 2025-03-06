<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GotMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $message)
    {
        // as we have public $massage as a constructor argument
        // so the in the frontend there we'll have e.message
        // in the .listen((e) => { ... }) callback
    }

    public function broadcastAs()
    {
        return 'GotMessage';
    }
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel("App.Models.User.{$this->message['user_id']}"),
            new Channel("channel_for_everyone"),
        ];
    }
}