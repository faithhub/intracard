<?php

namespace App\Jobs;

use App\Events\GotMessage;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Message $message)
    {
        //
    }

    public function handle(): void
    {
        GotMessage::dispatch([
            'id' => $this->message->id,
            'user_id' => $this->message->user_id,
            // 'text' => $this->message->text,
            // 'time' => $this->message->time,
                'ticket_id'   =>  $this->message->ticket_id,
                'sender_id'   =>  $this->message->sender_id,
                'sender_type' =>  $this->message->sender_type,
        ]);
    }
}