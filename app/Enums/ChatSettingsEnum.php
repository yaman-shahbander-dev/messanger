<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum ChatSettingsEnum: int
{
    use EnumHelper;

    CASE SEEN = 1;
    CASE NOTSEEN = 0;
}
