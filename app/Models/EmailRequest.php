<?php

namespace App\Models;

use App\Exceptions\MultiTypeBodyNotAllowedException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $this->body = $this->buildBody($request->get('text_content'), $request->get('html_content'));
        $this->attachments = $request->get('attachments', []);
    }

    /**
     * @param string $textContent
     * @param string $htmlContent
     * @return string
     * @throws ValidationException
     */
    private function buildBody(string $textContent, string $htmlContent)
    {
        if (!empty($textContent) && !empty($htmlContent)) {
            $message = 'Both text and html content are not allowed. please provide either text or html content';
            throw ValidationException::withMessages([
                'text_content' => $message,
                'html_content' => $message
            ]);
        }
        if (!empty($textContent)) {
            return $textContent;
        }
        return $htmlContent;
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