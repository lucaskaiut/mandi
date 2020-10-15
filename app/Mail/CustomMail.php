<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $file = null, $body)
    {
        $this->subject = $subject;
        $this->file = $file;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->subject;
        $file = $this->file;
        $body = $this->body;

        if ($file != null) {
            return $this
                ->view('panel.mail.custom', compact('body'))
                ->subject($subject)
                ->attach($file);
        } else {
            return $this
                ->view('panel.mail.custom', compact('body'))
                ->subject($subject);
        }


    }
}
