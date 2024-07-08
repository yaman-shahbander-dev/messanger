<?php

namespace App\Managers\IManagers;

use App\Strategies\IStrategies\ITimeAgoStrategy;

interface ITimeAgoStrategyManager
{
    public static function makeStrategy(string $timestamp): ITimeAgoStrategy;
}
