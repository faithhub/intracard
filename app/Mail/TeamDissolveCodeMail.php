<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamDissolveCodeMail extends Mailable
{
   public $code;

   public function __construct($code)
   {
       $this->code = $code;
   }

   public function build()
   {
       return $this->markdown('emails.team.dissolve-code')
                   ->subject('Team Dissolution Verification Code');
   }
}