<?php

namespace App\Builder\Director;

use App\Builder\IBuilder\IFileUploadBuilder;
use Illuminate\Http\Request;

class FileUploadDirector
{
    public function __construct(protected IFileUploadBuilder $builder) { }

    public function saveFile(Request $request, string $inputName, string $path): string
    {
        return $this->builder
            ->setFile($request, $inputName, $path)
            ->getFileExtension()
            ->generateFileName()
            ->saveFile()
            ->getFile();
    }
}
