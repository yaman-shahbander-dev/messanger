<?php

namespace App\Builder\Builder;

use App\Builder\IBuilder\IFileUploadBuilder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalUploadFileBuilder implements IFileUploadBuilder
{
    private UploadedFile $file;
    private string $extension;
    private string $fileName;
    private string $path;

    public function setFile(Request $request, string $inputName, string $path): self
    {
        $this->file = $request->{$inputName};
        $this->path = $path;
        return $this;
    }

    public function getFileExtension(): self
    {
        $this->extension = $this->file->getClientOriginalExtension();
        return $this;
    }

    public function generateFileName(): self
    {
        $this->fileName = 'media_' . uniqid() . '.' . $this->extension;
        return $this;
    }

    public function saveFile(): self
    {
        $this->file->move(public_path($this->path), $this->fileName);
        return $this;
    }

    public function getFile(): string
    {
        return $this->path . '/' . $this->fileName;
    }
}
