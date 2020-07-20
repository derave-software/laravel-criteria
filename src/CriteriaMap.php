<?php

namespace DeraveSoftware\LaravelCriteria;

use DeraveSoftware\LaravelCriteria\Criteria\ContainsCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\IsCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\MatchesCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\SortCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\TimeRangeCriterion;
use Illuminate\Support\Arr;

final class CriteriaMap
{
    protected static array $criteriaMap = [
        'contains' => ContainsCriterion::class,
        'matches' => MatchesCriterion::class,
        'is' => IsCriterion::class,
        'sort' => SortCriterion::class,
        'time_range' => TimeRangeCriterion::class,
    ];

    public static function get(string $key): string
    {
        return Arr::get(static::$criteriaMap, $key, '');
    }

    public static function set(array $map): void
    {
        static::$criteriaMap = $map;
    }

    public static function merge(array $map): void
    {
        static::$criteriaMap = static::$criteriaMap + $map;
    }

    public static function criteriaMap(): array
    {
        return static::$criteriaMap;
    }
}
