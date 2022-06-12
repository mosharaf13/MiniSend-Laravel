<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlainTextEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Request $request
     */
    public function __construct(public Request $request)
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->request->get('subject'))
            ->to($this->request->get('to'))
            ->from($this->request->get('from'))
            ->text('emails.plain_text');
    }
}
