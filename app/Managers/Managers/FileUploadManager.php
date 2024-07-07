<?php

namespace App\Managers\Managers;

use App\Builder\Builder\LocalUploadFileBuilder;
use App\Builder\IBuilder\IFileUploadBuilder;
use App\Managers\IManagers\IFileUploadManager;
use Illuminate\Support\Facades\App;

class FileUploadManager implements IFileUploadManager
{
    public static function makeBuilder(string $builderType): IFileUploadBuilder
    {
        $methodName = 'create' . ucfirst($builderType) . 'Builder';

        return static::{$methodName}();
    }

    private static function createLocalBuilder(): LocalUploadFileBuilder
    {
        return App::make(LocalUploadFileBuilder::class);
    }
}
