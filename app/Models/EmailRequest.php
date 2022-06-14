<?php

namespace App\Models;

use Illuminate\Http\Request;

class EmailRequest
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
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }


}