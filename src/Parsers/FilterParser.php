<?php

namespace DeraveSoftware\LaravelCriteria\Parsers;

use DeraveSoftware\LaravelCriteria\Concerns\ResolvesCriterionClass;
use DeraveSoftware\LaravelCriteria\CriteriaMap;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FilterParser
{
    use ResolvesCriterionClass;

    public function parse(array $filters, array $descriptions): Collection
    {
        $criteria = new Collection();

        foreach ($filters as $name => $value) {

            $filterDescription = Arr::get($descriptions, $name);

            if ($filterDescription === null) {
                continue;
            }

            [$filterName, $params] = $this->parseFilterDescription($filterDescription, $value);

            $criteria->push($this->resolveClass(CriteriaMap::get($filterName), ...$params));
        }

        return $criteria;
    }

    protected function parseFilterDescription(string $filterDescription, $value): array
    {
        $parsed = explode('|', $filterDescription);

        $filterName = Arr::get($parsed, 0, null);

        if (! $filterName) {
            throw new InvalidArgumentException('Filter name not specified');
        }

        $filterParams = explode(':', Arr::get($parsed, 1, ''));

        $params = array_merge(
            [$this->getFilterColumn($filterParams)],
            $this->getFilterParameters(Arr::get($filterParams, 1), $value)
        );

        return [$filterName, $params];
    }

    protected function getFilterColumn(array $filterParams): string
    {
        $column = Arr::get($filterParams, 0, null);

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
