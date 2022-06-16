<?php

namespace App\Models;

use App\Exceptions\BothTextAndHtmlBodyNotAllowedException;
use Illuminate\Http\Request;

class EmailRequest
{
    protected string $from;
    protected string $to;
    protected string $subject;
    protected string $body;
    protected array $attachments = [];
    protected string $bodyType;

    const BODY_TYPE_TEXT = 'text';
    const BODY_TYPE_HTML = 'html';

    public function __construct(Request $request)
    {
        $this->from = $request->get('from');
        $this->to = $request->get('to');
        $this->subject = $request->get('subject');
        $this->body = $this->buildBody($request->get('text_content'), $request->get('html_content'));
        $this->attachments = $request->file('attachments', []);
    }

    /**
     * @param string|null $textContent
     * @param string|null $htmlContent
     * @return string|null
     * @throws BothTextAndHtmlBodyNotAllowedException
     */
    private function buildBody(?string $textContent, ?string $htmlContent)
    {
        if (!empty($textContent) && !empty($htmlContent)) {
            $message = 'Both text and html content are not allowed. please provide either text or html content';
            throw new BothTextAndHtmlBodyNotAllowedException($message);
        }
        if (!empty($textContent)) {
            $this->bodyType = self::BODY_TYPE_TEXT;
            return $textContent;
        }
        $this->bodyType = self::BODY_TYPE_HTML;
        return $htmlContent;
    }

    /**
     * @return string
     */
    public function getBodyType(): string
    {
        return $this->bodyType;
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

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments = []): void
    {
        $this->attachments = $attachments;
    }


}