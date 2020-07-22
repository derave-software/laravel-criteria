<?php

namespace DeraveSoftware\LaravelCriteria\Parsers;

use DeraveSoftware\LaravelCriteria\Concerns\ResolvesCriterionClass;
use DeraveSoftware\LaravelCriteria\CriteriaMap;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

        $sorts = explode(',', $sortString);

        foreach ($sorts as $sort) {

            $sortParam = $this->getSortParam($sort);

            if(!in_array($sortParam[0], $allowedColumns)) {
                continue;
            }

            $criteria->push($this->resolveClass(
                $criterionClass,
                ...$sortParam
            ));
        }

        return $criteria;

    }

    protected function getSortParam(string $sort)
    {
        if(Str::startsWith($sort, '-')) {
            return [substr($sort, 1), 'desc'];
        } else {
            return [$sort, 'asc'];
        }

    }
}
