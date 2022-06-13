<?php

namespace App\Contracts;

use App\Models\EmailRequest;

interface EmailHandler
{
    public function storeInfoWithPostedStatus(EmailRequest $email): int;

    public function changeStatusToSent(int $emailIdInStorage);

    public function changeStatusToFailed(int $emailIdInStorage);
}