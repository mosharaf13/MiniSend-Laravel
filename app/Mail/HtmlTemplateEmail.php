<?php

namespace App\Mail;

use App\Models\EmailRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HtmlTemplateEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
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
        return $this->to($this->emailRequest->getTo())
            ->from($this->emailRequest->getFrom())
            ->subject($this->emailRequest->getSubject())
            ->html($this->emailRequest->getBody());
    }
}
