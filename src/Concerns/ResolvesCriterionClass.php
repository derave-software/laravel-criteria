<?php

namespace DeraveSoftware\LaravelCriteria\Concerns;

use DeraveSoftware\LaravelCriteria\Contracts\Criterion;
use InvalidArgumentException;
use ReflectionClass;
use Throwable;

trait ResolvesCriterionClass
{
    protected function resolveClass(string $className, ...$params): Criterion
    {
        try {
            $class = new ReflectionClass($className);
            if ($class->implementsInterface(Criterion::class)) {
                return new $className(...$params);
            }
        } catch (Throwable $exception) {
            throw new InvalidArgumentException('Class must implement ' . Criterion::class . ' interface');
        }

        throw new InvalidArgumentException('Class must implement ' . Criterion::class . ' interface');
    }
}
