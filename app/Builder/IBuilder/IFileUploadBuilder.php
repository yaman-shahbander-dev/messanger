<?php

namespace App\Builder\IBuilder;

use Illuminate\Http\Request;

interface IFileUploadBuilder
{
    public function setFile(Request $request, string $inputName, string $path): self;
    public function getFileExtension(): self;
    public function generateFileName(): self;
    public function saveFile(): self;
    public function getFile(): string;
}
