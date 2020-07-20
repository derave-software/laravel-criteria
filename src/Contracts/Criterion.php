<?php

namespace DeraveSoftware\LaravelCriteria\Contracts;

use Illuminate\Database\Query\Builder;

interface Criterion
{
    public function applyOn(Builder $builder): Criterion;
}
