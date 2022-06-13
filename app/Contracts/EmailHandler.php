<?php

namespace App\Contracts;

use App\Models\Email;

interface EmailHandler
{
    public function send(Email $email);

    public function storeEmailInfoWithPostedStatus(Email $email);
}