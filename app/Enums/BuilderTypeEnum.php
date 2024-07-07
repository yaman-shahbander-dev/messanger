<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum BuilderTypeEnum: string
{
    use EnumHelper;

    CASE LOCAL = 'local';
}
