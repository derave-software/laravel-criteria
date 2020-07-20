<?php

namespace DeraveSoftware\LaravelCriteria\Parsers;

use DeraveSoftware\LaravelCriteria\Concerns\ResolvesCriterionClass;
use DeraveSoftware\LaravelCriteria\CriteriaMap;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

class SortParser
{
    use ResolvesCriterionClass;

    public function parse(string $sortString, string $sortDescription): Collection
    {
        $criteria = collect();

        $sortDescription = explode('|', $sortDescription);

        if (count($sortDescription) !== 2) {
            return $criteria;
        }

        $criterionClass = CriteriaMap::get($sortDescription[0]);

        $allowedColumns = explode(',', Arr::get($sortDescription, 1, []));

        $sorts = explode(';', $sortString);

        foreach ($sorts as $sort) {
            $sortParams = $this->getSortParams($sort);

            if (! in_array($sortParams[0], $allowedColumns)) {
                continue;
            }

            $criteria->push($this->resolveClass(
                $criterionClass,
                ...$sortParams
            ));
        }

        return $criteria;

    }

    protected function getSortParams(string $sortString): array
    {
        $sortParams = explode(',', $sortString);

        if (count($sortParams) === 1) {
            array_push($sortParams, 'asc');
        }

        Assert::count($sortParams, 2);

        return $sortParams;
    }
}
