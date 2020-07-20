<?php

namespace DeraveSoftware\LaravelCriteria\Parsers;

use DeraveSoftware\LaravelCriteria\Requests\CriteriaRequest;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

class ParamsParser
{
    const FILTER_PARAM_NAME = 'filter';
    const SORT_PARAM_NAME = 'sort';

    protected FilterParser $filterParser;
    protected SortParser $sortParser;

    public function __construct()
    {
        $this->filterParser = new FilterParser();
        $this->sortParser = new SortParser();
    }

    public function parse(CriteriaRequest $request): Collection
    {
        $criteria = collect();

        $filterParams = $request->query(static::FILTER_PARAM_NAME, []);

        Assert::isArray($filterParams);

        return $criteria
            ->merge($this->filterParser->parse($filterParams, $request->filters()))
            ->merge($this->sortParser->parse($request->query(static::SORT_PARAM_NAME, ''), $request->sort()));
    }
}
