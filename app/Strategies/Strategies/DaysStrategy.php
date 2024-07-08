<?php

namespace App\Strategies\Strategies;

use App\Strategies\IStrategies\ITimeAgoStrategy;

class DaysStrategy implements ITimeAgoStrategy
{
    public function getTimeAgo(string $timestamp): string
    {
        return date('j M y', strtotime($timestamp));
    }
}
