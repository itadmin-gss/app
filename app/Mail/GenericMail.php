<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    private $template;
    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $template, array $data)
    {
        $this->subject = $subject;
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->template)
            ->from(config('app.admin_email'), config('app.from_email_name'))
            ->with($this->data);
    }
}
