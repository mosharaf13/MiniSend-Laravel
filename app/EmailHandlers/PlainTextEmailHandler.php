<?php

namespace App\EmailHandlers;

use App\Contracts\EmailHandler;
use App\Mail\PlainTextEmail;
use App\Models\Email;
use App\Models\Eloquent\Email as EloquentEmail;
use Illuminate\Support\Facades\Mail;

class PlainTextEmailHandler implements EmailHandler
{
    public function send(Email $email)
    {
        Mail::to($email->getTo())->send(
            new PlainTextEmail($email)
        );
    }

    public function storeEmailInfoWithPostedStatus(Email $email)
    {
        EloquentEmail::create([
            'from' => $email->getFrom(),
            'to' => $email->getTo(),
            'subject' => $email->getSubject(),
            'body' => $email->getBody(),
            'status' => EloquentEmail::STATUS_POSTED
        ]);
    }

}