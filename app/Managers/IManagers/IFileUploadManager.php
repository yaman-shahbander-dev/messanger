<?php

namespace App\Managers\IManagers;

use App\Builder\IBuilder\IFileUploadBuilder;

interface IFileUploadManager
{
    public static function makeBuilder(string $builderType): IFileUploadBuilder;
}
