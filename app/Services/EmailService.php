<?php

namespace App\Services;

use App\Contracts\EmailHandler;
use App\Contracts\EmailStorage;
use App\Jobs\EmailJob;
use App\Models\EmailRequest;

class EmailService
{
    public function send(EmailRequest $emailRequest, EmailHandler $emailHandler, EmailStorage $emailStorage)
    {
        $emailHandler->sanitizeEmailRequest($emailRequest);
        $emailIdInStorage = $emailStorage->storeInfoWithPostedStatus($emailRequest);
        EmailJob::dispatch($emailRequest, $emailIdInStorage, $emailHandler, $emailStorage);
    }
}