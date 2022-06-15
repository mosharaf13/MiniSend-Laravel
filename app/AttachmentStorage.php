<?php

namespace App;

use App\Contracts\AttachmentStorage as AttachmentStorageContract;
use App\Models\AttachmentMeta;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttachmentStorage implements AttachmentStorageContract
{

    /**
     * @param array $attachments
     * @param $emailIdInStorage
     * @return array
     */
    public function save(array $attachments, $emailIdInStorage): array
    {
        $attachmentsMeta = [];
        foreach ($attachments as $attachment) {
            array_push(
                $attachmentsMeta,
                new AttachmentMeta(
                    Storage::disk('public')->putFile('attachments', $attachment, 'public'),
                    $attachment->getClientOriginalName(),
                    $attachment->getClientOriginalExtension()
                )
            );
        }
        return $attachmentsMeta;
    }
}