<?php

namespace App\EmailHandlers;

use App\Contracts\EmailHandler;
use App\Contracts\EmailStorage;
use App\Mail\PlainTextEmail;
use App\Models\EmailRequest;
use Illuminate\Support\Facades\Mail;

class PlainTextEmailHandler implements EmailHandler
{

    /**
     * @param EmailRequest $emailRequest
     * @return EmailRequest
     */
    public function sanitizeEmailRequest(EmailRequest $emailRequest): EmailRequest
    {
        $emailRequest->setBody(
            trim(htmlspecialchars($emailRequest->getBody()))
        );

        return $emailRequest;
    }

    public function send(EmailRequest $emailRequest)
    {
        Mail::to($emailRequest->getTo())->send(
            new PlainTextEmail($emailRequest)
        );
    }


}