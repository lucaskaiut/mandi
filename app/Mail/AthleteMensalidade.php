<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AthleteMensalidade extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($urlFileToAttatch = null, $mensalidade)
    {
        $this->urlFileToAttatch = $urlFileToAttatch;
        $this->mensalidade = $mensalidade;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mensalidade = $this->mensalidade;
        return $this
            ->view('panel.mail.body_mensalidade', compact('mensalidade'))
            ->subject('Recibo - Mensalidade '. $this->mensalidade)
            ->attach($this->urlFileToAttatch);
    }
}
