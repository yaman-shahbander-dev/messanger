<?php

namespace App\Strategies\Strategies;

use App\Strategies\IStrategies\ITimeAgoStrategy;

class SecondsStrategy implements ITimeAgoStrategy
{
    public function getTimeAgo(string $timestamp): string
    {
        $seconds = time() - strtotime($timestamp);

        return $seconds . "s ago";
    }
}
