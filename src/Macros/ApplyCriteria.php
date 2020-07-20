<?php

namespace DeraveSoftware\LaravelCriteria\Macros;

use DeraveSoftware\LaravelCriteria\Contracts\Criterion;
use Illuminate\Database\Query\Builder;

class ApplyCriteria
{
    public static function register()
    {
        Builder::macro('applyCriteria', function ($criteria) {

            if ($criteria instanceof Criterion) {
                $criteria = [$criteria];
            }

            if (is_iterable($criteria)) {
                foreach ($criteria as $criterion) {
                    $criterion->applyOn($this);
                }
            }

            return $this;
        });
    }
}
