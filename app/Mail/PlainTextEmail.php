<?php

namespace App\Mail;

use App\Models\AttachmentMeta;
use App\Models\EmailRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
        $mailable = $this->subject($this->emailRequest->getSubject())
            ->to($this->emailRequest->getTo())
            ->from($this->emailRequest->getFrom())
            ->text('emails.plain_text', [
                'body' => htmlspecialchars_decode($this->emailRequest->getBody())
            ]);
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
