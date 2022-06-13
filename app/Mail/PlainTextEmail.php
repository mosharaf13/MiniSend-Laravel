<?php

namespace App\Mail;

use App\Models\EmailRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlainTextEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param EmailRequest $email
     */
    public function __construct(public EmailRequest $email)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->email->getSubject())
            ->to($this->email->getTo())
            ->from($this->email->getTo())
            ->text('emails.plain_text');
    }
}
