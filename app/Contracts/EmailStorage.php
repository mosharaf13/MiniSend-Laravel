<?php

namespace App\Contracts;

use App\Models\EmailRequest;

interface EmailStorage
{

    /**
     * @param EmailRequest $email
     * @return int
     */
    public function storeInfoWithPostedStatus(EmailRequest $email): int;

    /**
     * @param $emailIdInStorage
     * @return mixed
     */
    public function changeStatusToSent($emailIdInStorage): void;

    /**
     * @param $emailIdInStorage
     */
    public function changeStatusToFailed($emailIdInStorage): void;

    /**
     * @param array $attachmentsMeta
     */
    public function saveAttachmentMeta(array $attachmentsMeta, $emailIdInStorage): void;
}