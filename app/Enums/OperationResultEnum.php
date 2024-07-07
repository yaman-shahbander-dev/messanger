<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum OperationResultEnum: string
{
    use EnumHelper;

    CASE SUCCESS = 'success';
    CASE FAILURE = 'failure';
}
