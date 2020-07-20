# Laravel Criteria

[![GitHub Workflow Status](https://github.com/derave-software/laravel-criteria/workflows/Run%20tests/badge.svg)](https://github.com/derave-software/laravel-criteria/actions)

[![Packagist](https://img.shields.io/packagist/v/derave-software/laravel-criteria.svg)](https://packagist.org/packages/derave-software/laravel-criteria)
[![Packagist](https://poser.pugx.org/derave-software/laravel-criteria/d/total.svg)](https://packagist.org/packages/derave-software/laravel-criteria)
[![Packagist](https://img.shields.io/packagist/l/derave-software/laravel-criteria.svg)](https://packagist.org/packages/derave-software/laravel-criteria)

Package description: This package was inspired by [spatie/laravel-query-builder](https://github.com/spatie/laravel-query-builder). What it does different it's not bound to Request class.
You can use criteria to build your query easier and cleaner than inline.


## Installation

Install via composer
```bash
composer require derave-software/laravel-criteria
```

### Publish package assets

```bash
php artisan vendor:publish --provider="DeraveSoftware\LaravelCriteria\LaravelCriteriaServiceProvider"
```

## Usage
It can be used on two ways:

### Applying directly on  model
Package provides `applyCriteria` macro to Builder class which allows you to add criteria to builder.
So you can just write:

```php
use DeraveSoftware\LaravelCriteria\Criteria\ContainsCriterion;
use DeraveSoftware\LaravelCriteria\Criteria\TimeRangeCriterion;
use App\User;

$contains = new ContainsCriterion('name', 'prof');
$timeRange = new TimeRangeCriterion('logged_in', '2020-01-01', null);
$results = User::applyCriteria([$contains, $timeRange])->get();

//Get all users with name containing string prof that were logged into system in 2020
```

### Create criteria based on request query
Package provides `CriteriaRequest` class which you can extend and define to what criteria request's query should be parsed with format known from request rules.

Above example could be written like below:
```php
use App\Http\Controllers\Controller;
use App\User;
use DeraveSoftware\LaravelCriteria\Requests\CriteriaRequest;

class UsersLoggedSinceRequest extends CriteriaRequest
{
    public function filters(): array
    {
        return [
            'name' => 'contains|name',
            'since' => 'time_range|logged_in:from,to'
        ];
    }
}

class UsersController extends Controller
{
    public function loggedSince(UsersLoggedSinceRequest $request)
    {
        $results = User::applyCriteria($request->criteria())->get();
    }
}

//?filter[name]=prof&filter[since][from]=2020-01-01&filter[since][to]=
```

Criteria filter string description has format as follows:

`criterion_name|column:indexes,coma,separated`
If criterion needs more than single value you can define what indexes will be send in filter query param. If param key will not be found in query null value will be applied.

### Predefined criteria

### Contains criterion
Contains criterion allows you to search for records where specified column contains string passed in second parameter - simple "where like %search%"

```php
use DeraveSoftware\LaravelCriteria\Criteria\ContainsCriterion;

new ContainsCriterion('name', 'prof');

//Will search for all records which column name contains string 'prof'
```

### Matches criterion
Matches criterion is similar to contains but it searches for exact match

```php
use DeraveSoftware\LaravelCriteria\Criteria\MatchesCriterion;

new MatchesCriterion('name', 'prof');

//Will search for all records which column name equals 'prof'
```

### Is criterion
Is criterion allows you to search for boolean values

```php
use DeraveSoftware\LaravelCriteria\Criteria\IsCriterion;

new IsCriterion('is_active', true);

//Will search for all records which column name equals true
```

### Time range criterion
Time range criterion allows you to search for given time range. It also supports "since" and "until".
 If `from` is specified and `to` set to `null` it will search for all records with date greater than or equal to date given in param.
If `to` is specified and `from` set to `null`  it will search for all records with date lower than or equal to date given in param.
If both parameters are specified it will check if `to` is greater than `from` and perform search for dates in given range

```php
use DeraveSoftware\LaravelCriteria\Criteria\TimeRangeCriterion;

new TimeRangeCriterion('logged_in', '2010-01-01', '2010-12-31');

//Will search for all records with column between given dates
```

### Sorting
Package also provides sorting criterion. You are allowed to add as many sorts as you want to. Sorting can be also parsed from request query

Given the same example:
```php
use DeraveSoftware\LaravelCriteria\Requests\CriteriaRequest;

class UsersLoggedSinceRequest extends CriteriaRequest
{
    public function filters(): array
    {
        return [
            'name' => 'contains|name',
            'since' => 'time_range|logged_in:from,to'
        ];
    }

    public function sort(): string
    {
        return 'sort|name,logged_in';
    }

}
```

In `sort` method of your request you can specify criterion responsible for sorting and columns that request is allowed to sort on.
In example above criterion responsible for sorting will be SortCriterion and columns that it is allowed to sort on are `name` and `logged_in`

### Creating your own criteria

You can create your own criteria classes all you need to do is to implement `Criterion` interface.

```php
use \DeraveSoftware\LaravelCriteria\Contracts\Criterion;
use \Illuminate\Database\Query\Builder;

class IsNotCriterion implements Criterion
{
    protected string $column;
    protected string $search;

    public function __construct(string $column, bool $search)
    {
        $this->column = $column;
        $this->search = $search;
    }

    public function applyOn(Builder $builder): Criterion
    {
        $builder->where($this->column, '!=', (int)$this->search);

        return $this;
    }
}
```
If you would like your criterion to be parsed in Request you need to register it in `CriteriaMap` in you service provider
For simplicity of parsing I've assumed that column will be always your first parameter in criterion constructor.

```php

use \DeraveSoftware\LaravelCriteria\CriteriaMap;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        ...
        CriteriaMap::merge(['is_not' => IsNotCriterion::class]);
    }

}
```

## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits

- [](https://github.com/derave-software/laravel-criteria)
- [All contributors](https://github.com/derave-software/laravel-criteria/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
