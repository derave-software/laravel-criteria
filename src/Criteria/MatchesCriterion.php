<?php

namespace DeraveSoftware\LaravelCriteria\Criteria;

use DeraveSoftware\LaravelCriteria\Contracts\Criterion;
use Illuminate\Database\Query\Builder;

class MatchesCriterion implements Criterion
{
    protected string $column;
    protected string $search;

    /**
     * ContainsCriterion constructor.
     * @param string $column
     * @param string $search
     */
    public function __construct(string $column, string $search)
    {
        $this->column = $column;
        $this->search = $search;
    }

    public function applyOn(Builder $builder): Criterion
    {
        $builder->where($this->column, 'like', $this->search);

        return $this;
    }
}
