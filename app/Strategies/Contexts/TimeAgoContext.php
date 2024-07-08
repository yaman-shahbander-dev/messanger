<?php

namespace App\Strategies\Contexts;

use App\Managers\Managers\TimeAgoStrategyManager;
use App\Strategies\IStrategies\ITimeAgoStrategy;

class TimeAgoContext
{
    protected ITimeAgoStrategy $strategy;

    public function getTimeAgo(string $timestamp): string
    {
        $this->strategy = TimeAgoStrategyManager::makeStrategy($timestamp);
        return $this->strategy->getTimeAgo($timestamp);
    }
}
