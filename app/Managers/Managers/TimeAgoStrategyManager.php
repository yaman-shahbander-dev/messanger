<?php

namespace App\Managers\Managers;

use App\Managers\IManagers\ITimeAgoStrategyManager;
use App\Strategies\IStrategies\ITimeAgoStrategy;
use App\Strategies\Strategies\DaysStrategy;
use App\Strategies\Strategies\HoursStrategy;
use App\Strategies\Strategies\MinutesStrategy;
use App\Strategies\Strategies\SecondsStrategy;
use Illuminate\Support\Facades\App;

class TimeAgoStrategyManager implements ITimeAgoStrategyManager
{
    public static function makeStrategy(string $timestamp): ITimeAgoStrategy
    {
        $timeDifference = time() - strtotime($timestamp);

        return match (true) {
            $timeDifference <= 60 => static::createSecondsStrategy($timeDifference),
            $timeDifference <= 3600 => static::createMinutesStrategy($timeDifference),
            $timeDifference <= 86400 => static::createHoursStrategy($timeDifference),
            default => static::createDaysStrategy($timeDifference),
        };
    }

    public static function createSecondsStrategy(): SecondsStrategy
    {
        return App::make(SecondsStrategy::class);
    }

    public static function createMinutesStrategy(): MinutesStrategy
    {
        return App::make(MinutesStrategy::class);
    }

    public static function createHoursStrategy(): HoursStrategy
    {
        return App::make(HoursStrategy::class);
    }

    public static function createDaysStrategy(): DaysStrategy
    {
        return App::make(DaysStrategy::class);
    }
}
