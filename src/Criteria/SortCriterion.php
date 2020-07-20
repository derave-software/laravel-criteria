<?php

namespace DeraveSoftware\LaravelCriteria\Criteria;

use DeraveSoftware\LaravelCriteria\Contracts\Criterion;
use Illuminate\Database\Query\Builder;
use Webmozart\Assert\Assert;

class SortCriterion implements Criterion
{
    protected string $order;
    protected string $column;

    public function __construct(string $column, string $order)
    {
        Assert::inArray($order, ['desc', 'asc']);

        $this->column = $column;
        $this->order = $order;
    }

    public function applyOn(Builder $builder): Criterion
    {
        $builder->orderBy($this->column, $this->order);

        return $this;
    }
}
