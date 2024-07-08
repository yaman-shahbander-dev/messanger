<?php

namespace App\Strategies\Strategies;

use App\Strategies\IStrategies\ITimeAgoStrategy;

class MinutesStrategy implements ITimeAgoStrategy
{
    public function getTimeAgo(string $timestamp): string
    {
        $timeDifference = time() - strtotime($timestamp);

        $minutes = round($timeDifference / 60);

        return $minutes . "m ago";
    }
}
