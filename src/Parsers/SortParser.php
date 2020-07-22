<?php

namespace DeraveSoftware\LaravelCriteria\Parsers;

use DeraveSoftware\LaravelCriteria\Concerns\ResolvesCriterionClass;
use DeraveSoftware\LaravelCriteria\Consts\SortOrder;
use DeraveSoftware\LaravelCriteria\CriteriaMap;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SortParser
{
    use ResolvesCriterionClass;

    const CRITERION_CLASS_INDEX = 0;
    const ALLOWED_COLLUMNS_INDEX = 0;
    const COLUMN_NAME_INDEX = 0;

    public function parse(string $sortString, string $sortDescription): Collection
    {
        $criteria = collect();

        $sortDescription = explode('|', $sortDescription);

        if (count($sortDescription) !== 2) {
            return $criteria;
        }

        $criterionClass = CriteriaMap::get($sortDescription[static::CRITERION_CLASS_INDEX]);

        $allowedColumns = explode(',', Arr::get($sortDescription, static::ALLOWED_COLLUMNS_INDEX, []));

        $sorts = explode(',', $sortString);

        foreach ($sorts as $sort) {

            $sortParam = $this->getSortParam($sort);

            if(!in_array($sortParam[static::COLUMN_NAME_INDEX], $allowedColumns)) {
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
            return [substr($sort, 1), SortOrder::DESC];
        } else {
            return [$sort, SortOrder::ASC];
        }

    }
}
