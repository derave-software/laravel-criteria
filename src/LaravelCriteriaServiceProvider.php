<?php

namespace DeraveSoftware\LaravelCriteria;

use DeraveSoftware\LaravelCriteria\Criteria\ContainsCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\IsCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\EqualsCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\SortCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\TimeRangeCriterion;
use DeraveSoftware\LaravelCriteria\Macros\ApplyCriteria;
use Illuminate\Support\ServiceProvider;

class LaravelCriteriaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        CriteriaMap::set([
            'contains' => ContainsCriterion::class,
            'equals' => EqualsCriterion::class,
            'is' => IsCriterion::class,
            'sort' => SortCriterion::class,
            'time_range' => TimeRangeCriterion::class,
        ]);
    }

    public function register()
    {
        $this->registerMacros();

        $this->app->singleton(CriteriaMap::class);
    }

    protected function registerMacros(): void
    {
        ApplyCriteria::register();
    }
}
