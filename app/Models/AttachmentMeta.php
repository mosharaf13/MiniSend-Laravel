<?php

namespace App\Models;

class AttachmentMeta
{

    public function __construct(protected string $path, protected string $originalName, protected string $extension)
    {

    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

}