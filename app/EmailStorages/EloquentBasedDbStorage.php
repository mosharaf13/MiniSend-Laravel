<?php

namespace App\EmailStorages;

use App\Contracts\EmailStorage;
use App\Models\AttachmentMeta;
use App\Models\Eloquent\Email as EloquentEmail;
use App\Models\Eloquent\EmailAttachment;
use App\Models\EmailRequest;

class EloquentBasedDbStorage implements EmailStorage
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
     * @param $emailIdInStorage
     */
    public function changeStatusToSent($emailIdInStorage): void
    {
        EloquentEmail::where('id', $emailIdInStorage)->update(['status' => EloquentEmail::STATUS_SENT]);
    }

    /**
     * @param $emailIdInStorage
     */
    public function changeStatusToFailed($emailIdInStorage): void
    {
        EloquentEmail::where('id', $emailIdInStorage)->update(['status' => EloquentEmail::STATUS_FAILED]);
    }

    /**
     * @param AttachmentMeta[] $attachmentsMeta
     * @param $emailIdInStorage
     */
    public function saveAttachmentMeta(array $attachmentsMeta, $emailIdInStorage): void
    {
        foreach ($attachmentsMeta as $meta) {
            EmailAttachment::create([
                'path' => $meta->getPath(),
                'original_name' => $meta->getOriginalName(),
                'extension' => $meta->getExtension(),
                'email_id' => $emailIdInStorage
            ]);
        }
    }

}