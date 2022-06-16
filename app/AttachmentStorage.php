<?php

namespace App;

use App\Contracts\AttachmentStorage as AttachmentStorageContract;
use App\Exceptions\FileNotFoundInStorageException;
use App\Models\AttachmentMeta;
use Illuminate\Support\Facades\Storage;

class AttachmentStorage implements AttachmentStorageContract
{
    const DISK_NAME = 'public';


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
                    Storage::disk(self::DISK_NAME)->putFile('attachments', $attachment, 'public'),
                    $attachment->getClientOriginalName(),
                    $attachment->getClientOriginalExtension()
                )
            );
        }
        return $attachmentsMeta;
    }

    /**
     * @param $attachmentPath
     * @param null $name
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws FileNotFoundInStorageException
     */
    public function download($attachmentPath, $name = null)
    {
        if (!Storage::disk(self::DISK_NAME)->exists($attachmentPath)) {
            throw new FileNotFoundInStorageException("File not found in storage", 404);
        }
        return Storage::disk('public')->download(
            $attachmentPath,
            $name
        );
    }
}