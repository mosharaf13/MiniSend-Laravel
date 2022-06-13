<?php

namespace App\Services;

use App\Contracts\EmailHandler;
use App\Models\Email;

class EmailService
{
    public function send(Email $email, EmailHandler $emailHandler)
    {
        $emailHandler->storeEmailInfoWithPostedStatus($email);
        $emailHandler->send($email);
    }
}