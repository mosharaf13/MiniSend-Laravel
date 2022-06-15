<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface AttachmentStorage
{
    /**
     * @param UploadedFile[] $attachments
     * @param  $emailIdInStorage
     * @return mixed
     */
    public function save(array $attachments, $emailIdInStorage);
}