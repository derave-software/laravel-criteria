<?php

namespace DeraveSoftware\LaravelCriteria\Criteria;

use Carbon\Carbon;
use DeraveSoftware\LaravelCriteria\Contracts\Criterion;
use Illuminate\Database\Query\Builder;
use Webmozart\Assert\Assert;

class TimeRangeCriterion implements Criterion
{
    protected string $column;
    protected ?Carbon $from;
    protected ?Carbon $to;

    /**
     * ContainsCriterion constructor.
     * @param string $column
     * @param string $search
     */
    public function __construct(string $column, ?string $from, ?string $to)
    {

        $this->column = $column;
        $this->from = $from ? Carbon::parse($from) : null;
        $this->to = $to ? Carbon::parse($to) : null;

        if ($this->from && $this->to) {
            Assert::true($this->from->lt($this->to));
        }
    }

    public function applyOn(Builder $builder): Criterion
    {
        if ($this->from === null) {
            $builder->whereDate($this->column, '<=', $this->to);
        } else {
            if ($this->to === null) {
                $builder->whereDate($this->column, '>=', $this->from);
            } else {
                $builder->whereBetween($this->column, [$this->from, $this->to]);
            }
        }

        return $this;
    }
}
