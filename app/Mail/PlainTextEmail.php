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
     * @param EmailRequest $emailRequest
     */
    public function __construct(public EmailRequest $emailRequest)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->emailRequest->getSubject())
            ->to($this->emailRequest->getTo())
            ->from($this->emailRequest->getFrom())
            ->text('emails.plain_text', [
                'body' => htmlspecialchars_decode($this->emailRequest->getBody())
            ]);
    }
}
