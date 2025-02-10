<?php

namespace App\Mail;

use App\Models\TeamMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminTransferMail extends Mailable
{
   public $teamMember;

   public function __construct(TeamMember $teamMember)
   {
       $this->teamMember = $teamMember;
   }

   public function build()
   {
       return $this->markdown('emails.team.admin-transfer')
                   ->subject('You Are Now Team Admin');
   }
}

