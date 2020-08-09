<?php

namespace DeraveSoftware\LaravelCriteria\Tests;

use DeraveSoftware\LaravelCriteria\Criteria\EqualsCriterion;
use DeraveSoftware\LaravelCriteria\Tests\Models\Product;

class EqualsCriterionTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCanFilterByEqualsCriterion(string $column, array $values, $search)
    {
        foreach ($values as $value) {
            factory(Product::class)->create([
                $column => $value,
            ]);
        }

        $SUT = new EqualsCriterion($column, $search);
        $products = Product::applyCriteria($SUT)->get();

        $this->assertCount(1, $products);
    }

    public function dataProvider()
    {
        return [
            'string column value' => ['name', ['match', 'other'], 'match'],
            'integer column value' => ['price', [100, 399], 100],
            'boolean column value' => ['is_visible', [true, false], true],
        ];
    }
}
