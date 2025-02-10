<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $name;

    /**
     * Create a new message instance.
     *
     * @param string $code
     * @param string $name
     */
    public function __construct($code, $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Verification Code for IntraCard')
                    ->view('emails.verification_code');
    }
}
