<?php

namespace App\Models;

use Illuminate\Http\Request;

class Email
{
    private string $from;
    private string $to;
    private string $subject;
    private string $body;
    private array $attachments = [];

    public function __construct(Request $request)
    {
        $this->from = $request->get('from');
        $this->to = $request->get('to');
        $this->subject = $request->get('subject');
        $this->body = $request->get('body');
        $this->attachments = $request->get('attachments', []);
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }


}