<?php

namespace App\Strategies\Strategies;

use App\Strategies\IStrategies\ITimeAgoStrategy;

class HoursStrategy implements ITimeAgoStrategy
{
    public function getTimeAgo(string $timestamp): string
    {
        $timeDifference = time() - strtotime($timestamp);

        $hours = round($timeDifference / 3600);

        return $hours . "h ago";
    }
}
