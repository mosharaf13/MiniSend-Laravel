<?php

namespace App\Contracts;

use App\Models\EmailRequest;

interface EmailHandler
{
    public function sanitizeEmailRequest(EmailRequest $emailRequest): EmailRequest;

    public function send(EmailRequest $emailRequest);
}