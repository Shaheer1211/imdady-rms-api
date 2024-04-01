<?php

namespace App\Filters;

class IngredientCategoriesFilter extends ApiFilter {
    protected $safeParms = [
        'category_name' => ['eq'],
        'user_id' => ['eq'],
        'outlet_id' => ['eq'],
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
}