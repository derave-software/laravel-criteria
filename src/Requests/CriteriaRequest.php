<?php

namespace DeraveSoftware\LaravelCriteria\Requests;

use DeraveSoftware\LaravelCriteria\Parsers\ParamsParser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class CriteriaRequest extends FormRequest
{
    public function rules()
    {
        return [];
    }

    public function filters(): array
    {
        return [];
    }

    public function sort(): string
    {
        return '';
    }

    public function criteria(): Collection
    {
        $parser = new ParamsParser();

        return $parser->parse($this);
    }
}
