<?php

namespace App\GraphQL\Types\Common;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class OptionType extends BaseType
{

    protected $attributes = [
        'name'        => 'Option',
        'description' => '选项',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'name'             => [
                'type'        => Type::id(),
                'description' => '名称',
            ],
            'value'           => [
                'type'        => Type::string(),
                'description' => '值',
            ],
        ];
    }
}
