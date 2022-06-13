<?php

namespace App\EmailHandlers;

use App\Contracts\EmailHandler;
use App\Jobs\EmailJob;
use App\Models\Eloquent\Email as EloquentEmail;
use App\Models\EmailRequest;

class PlainTextEmailHandler implements EmailHandler
{
    /**
     * @param EmailRequest $email
     * @return int
     */
    public function storeInfoWithPostedStatus(EmailRequest $email): int
    {
        $email = EloquentEmail::create([
            'from' => $email->getFrom(),
            'to' => $email->getTo(),
            'subject' => $email->getSubject(),
            'body' => $email->getBody(),
            'status' => EloquentEmail::STATUS_POSTED
        ]);

        return $email->id;
    }

    /**
     * @param int $emailIdInStorage
     */
    public function changeStatusToSent(int $emailIdInStorage)
    {
        EloquentEmail::where('id', $emailIdInStorage)->update(['status' => EloquentEmail::STATUS_SENT]);
    }

    /**
     * @param int $emailIdInStorage
     */
    public function changeStatusToFailed(int $emailIdInStorage)
    {
        EloquentEmail::where('id', $emailIdInStorage)->update(['status' => EloquentEmail::STATUS_FAILED]);
    }


}