<?php

namespace DeraveSoftware\LaravelCriteria\Parsers;

use DeraveSoftware\LaravelCriteria\Concerns\ResolvesCriterionClass;
use DeraveSoftware\LaravelCriteria\CriteriaMap;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FilterParser
{
    const CRITERION_NAME_INDEX = 0;
    const FILTERED_COLUMN_NAME_INDEX = 0;
    const FILTER_DESCRIPTION_INDEX = 0;
    const ADDITIONAL_FILTER_PARAMETERS_INDEX = 0;

    use ResolvesCriterionClass;

    public function parse(array $filters, array $descriptions): Collection
    {
        $criteria = new Collection();

        foreach ($filters as $name => $value) {

            $filterDescription = Arr::get($descriptions, $name);

            if ($filterDescription === null) {
                continue;
            }

            [$criteronName, $params] = $this->parseFilterDescription($filterDescription, $value);

            $criteria->push($this->resolveClass(CriteriaMap::get($criteronName), ...$params));
        }

        return $criteria;
    }

    protected function parseFilterDescription(string $filterDescription, $value): array
    {
        $parsed = explode('|', $filterDescription);

        $criteronName = Arr::get($parsed, static::CRITERION_NAME_INDEX, null);

        if (! $criteronName) {
            throw new InvalidArgumentException('Filter name not specified');
        }

        $filterParams = explode(':', Arr::get($parsed, static::FILTER_DESCRIPTION_INDEX, ''));

        $params = array_merge(
            [$this->getFilterColumn($filterParams)],
            $this->getFilterParameters(Arr::get($filterParams, static::ADDITIONAL_FILTER_PARAMETERS_INDEX), $value)
        );

        return [$criteronName, $params];
    }

    protected function getFilterColumn(array $filterParams): string
    {
        $column = Arr::get($filterParams, static::FILTERED_COLUMN_NAME_INDEX, null);

        if (! $column) {
            throw new InvalidArgumentException('Filter column not specified');
        }

        return $column;
    }

    protected function getFilterParameters(?string $paramsDescription, $filterValue): array
    {
        $keys = isset($paramsDescription) ? explode(',', $paramsDescription) : null;
        $params = [];

        if (empty($keys)) {
            $params[] = $filterValue;
        } else {
            if (! is_array($filterValue)) {
                $filterValue = [$filterValue];
            }

            foreach ($keys as $key) {
                $params[] = $filterValue[$key] ?? null;
            }
        }

        return $params;
    }
}
