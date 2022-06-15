<?php

namespace App\Services;

use App\AttachmentStorage;
use App\Contracts\EmailHandler;
use App\Contracts\EmailStorage;
use App\Jobs\EmailJob;
use App\Models\EmailRequest;

class EmailService
{

    public function __construct(public AttachmentStorage $attachmentStorage)
    {
    }

    public function send(EmailRequest $emailRequest, EmailHandler $emailHandler, EmailStorage $emailStorage)
    {
        $emailHandler->sanitizeEmailRequest($emailRequest);

        $emailIdInStorage = $emailStorage->storeInfoWithPostedStatus($emailRequest);
        $attachmentsMeta = $this->attachmentStorage->save($emailRequest->getAttachments(), $emailIdInStorage);
        $emailStorage->saveAttachmentMeta($attachmentsMeta, $emailIdInStorage);

        $emailRequest->setAttachments($attachmentsMeta);
        EmailJob::dispatch($emailRequest, $emailIdInStorage, $emailHandler, $emailStorage);
    }
}