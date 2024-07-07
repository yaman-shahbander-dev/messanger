<?php

namespace App\Traits;

use App\Enums\BuilderTypeEnum;
use App\Managers\Managers\FileUploadManager;
use Illuminate\Http\Request;
use App\Builder\Director\FileUploadDirector;

trait FileUpload
{
    public function uploadFile(Request $request, string $inputName, string $path = '/uploads', ?string $oldPath = null, string $builderType = BuilderTypeEnum::LOCAL->value): ?string
    {
        if ($request->hasFile($inputName)) {
            $builder = FileUploadManager::makeBuilder($builderType);
            $director = new FileUploadDirector($builder);
            return $director->saveFile($request, $inputName, $path);
        }

        return null;
    }
}
