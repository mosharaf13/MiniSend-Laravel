<?php

namespace App\Mail;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlainTextEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Email $email
     */
    public function __construct(public Email $email)
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
