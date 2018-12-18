<?php

namespace App\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Illuminate\Pagination\AbstractPaginator;

class PaginationType extends ObjectType
{
    /**
     * PaginationType constructor.
     * @param String $typeName
     */
    public function __construct($typeName)
    {
        parent::__construct([
            'name'   => $typeName.'Pagination',
            'fields' => [
                'items'  => [
                    'type'    => Type::listOf(\GraphQL::type($typeName)),
                    'resolve' => function (AbstractPaginator $data) {
                        return $data->getCollection();
                    },
                ],
                'cursor' => [
                    'type'    => app('type-registry')->dynamicPaginationCursorType(),
                    'resolve' => function (AbstractPaginator $paginator) {
                        return $paginator;
                    },
                ],
            ],
        ]);
    }
}
