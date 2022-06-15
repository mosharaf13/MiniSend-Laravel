<?php

namespace App\Mail;

use App\Models\AttachmentMeta;
use App\Models\EmailRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
        $mailable = $this->to($this->emailRequest->getTo())
            ->from($this->emailRequest->getFrom())
            ->subject($this->emailRequest->getSubject())
            ->html($this->emailRequest->getBody());

        /**
         * @var AttachmentMeta $attachment
         */
        foreach ($this->emailRequest->getAttachments() as $attachment) {
            $mailable->attach(
                Storage::disk('public')->path($attachment->getPath()),
                [
                    'as' => $attachment->getOriginalName()
                ]
            );
        }
        return $mailable;
    }
}
