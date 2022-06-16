<?php

namespace App\Http\Controllers;

use App\AttachmentStorage;
use App\Models\Eloquent\EmailAttachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{

    public function __construct(public AttachmentStorage $attachmentStorage)
    {
    }

    /**
     * @param $attachmentId
     * @return string
     * @throws \App\Exceptions\FileNotFoundInStorageException
     */
    public function downloadAttachment($attachmentId)
    {
        $attachment = EmailAttachment::findOrFail($attachmentId);
        return $this->attachmentStorage->download($attachment->path, $attachment->original_name);
    }

}
