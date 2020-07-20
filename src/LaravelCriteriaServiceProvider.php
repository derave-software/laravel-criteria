<?php

namespace DeraveSoftware\LaravelCriteria;

use DeraveSoftware\LaravelCriteria\Macros\ApplyCriteria;
use Illuminate\Support\ServiceProvider;

class LaravelCriteriaServiceProvider extends ServiceProvider
{
    public function boot()
    {
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
