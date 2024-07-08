<?php

namespace App\Strategies\IStrategies;

interface ITimeAgoStrategy
{
    public function getTimeAgo(string $timestamp): string;
}
