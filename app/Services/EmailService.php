<?php

namespace App\Services;

use App\Contracts\EmailHandler;
use App\Jobs\EmailJob;
use App\Models\EmailRequest;

class EmailService
{
    public function send(EmailRequest $emailRequest, EmailHandler $emailHandler)
    {
        $emailHandler->sanitizeEmailRequest($emailRequest);
        $emailIdInStorage = $emailHandler->storeInfoWithPostedStatus($emailRequest);
        EmailJob::dispatch($emailRequest, $emailIdInStorage, $emailHandler);
    }
}