<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendJobEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        $mail = $this->markdown('send_email')->subject('Blog create notification email');

        return $mail;
    }
}
