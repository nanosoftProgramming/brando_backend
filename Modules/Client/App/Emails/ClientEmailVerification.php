<?php

namespace Modules\Client\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientEmailVerification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $verify_code;

    /**
     * Create a new message instance.
     */
    public function __construct($verify_code)
    {
        $this->verify_code = $verify_code;
        $this->onConnection('database');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('client::emails.client_email_verification', ['verify_code' => $this->verify_code]);
    }
}
