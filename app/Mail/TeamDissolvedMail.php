<?php

namespace App\Mail;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamDissolvedMail extends Mailable
{
   public $team;

   public function __construct(Team $team)
   {
       $this->team = $team;
   }

   public function build()
   {
       return $this->markdown('emails.team.dissolved')
                   ->subject('Team Has Been Dissolved');
   }
}