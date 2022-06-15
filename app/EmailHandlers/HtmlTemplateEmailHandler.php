<?php

namespace App\EmailHandlers;

use App\Contracts\EmailHandler;
use App\Mail\HtmlTemplateEmail;
use App\Mail\PlainTextEmail;
use App\Models\EmailRequest;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HtmlTemplateEmailHandler implements EmailHandler
{

    public function sanitizeEmailRequest(EmailRequest $emailRequest): EmailRequest
    {
        $htmlSanitizer = $this->createHtmlSanitizer();
        $emailRequest->setBody(
            nl2br($htmlSanitizer->sanitize($emailRequest->getBody()))
        );

        return $emailRequest;
    }

    private function createHtmlSanitizer(): HtmlSanitizer
    {
        return new HtmlSanitizer(
            (new HtmlSanitizerConfig())
                ->allowSafeElements()
                ->allowStaticElements()
                ->allowRelativeLinks()
                ->allowRelativeMedias()
        );
    }

    public function send(EmailRequest $emailRequest)
    {
        Mail::to($emailRequest->getTo())->send(
            new HtmlTemplateEmail($emailRequest)
        );
    }


}