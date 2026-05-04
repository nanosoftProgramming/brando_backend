<?php

namespace Modules\Common\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->onConnection('database');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('common::emails.contact-us')->with('data', $this->data)->subject('Contact Us Form Submission');
    }
}
