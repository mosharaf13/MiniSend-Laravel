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
     * @param int $emailIdInStorage
     * @return mixed
     */
    public function changeStatusToSent(int $emailIdInStorage): void;

    /**
     * @param int $emailIdInStorage
     */
    public function changeStatusToFailed(int $emailIdInStorage): void;
}