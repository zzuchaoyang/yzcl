<?php

namespace App\GraphQL\Types;

class TypeRegistry
{
    private $dynamicPaginationCursorType;

    public function dynamicPaginationCursorType()
    {
        return $this->dynamicPaginationCursorType ?: ($this->dynamicPaginationCursorType = new DynamicPaginationCursorType());
    }
}
