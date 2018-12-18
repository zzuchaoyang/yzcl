<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as GraphQLType;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class DynamicPaginationCursorType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name'   => 'DynamicPaginationCursor',
            'fields' => [
                'total'       => [
                    'type'    => GraphQLType::nonNull(GraphQLType::int()),
                    'resolve' => function (AbstractPaginator $paginator) {
                        if ($paginator instanceof LengthAwarePaginator) {
                            return $paginator->total();
                        }

                        return 0;
                    },
                ],
                'perPage'     => [
                    'type'    => GraphQLType::nonNull(GraphQLType::int()),
                    'resolve' => function (AbstractPaginator $paginator) {
                        return $paginator->perPage();
                    },
                ],
                'currentPage' => [
                    'type'    => GraphQLType::nonNull(GraphQLType::int()),
                    'resolve' => function (AbstractPaginator $paginator) {
                        return $paginator->currentPage();
                    },
                ],
                'hasPages'    => [
                    'type'    => GraphQLType::nonNull(GraphQLType::boolean()),
                    'resolve' => function (AbstractPaginator $paginator) {
                        return $paginator->hasPages();
                    },
                ],
            ],
        ]);
    }
}
