<?php

namespace App\GraphQL\Types\Customer\SalesmanForCooperation;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class SalesmanForCooperationType extends BaseType
{
    protected $attributes = [
        'name'        => 'SalesmanForCooperation',
        'description' => '为合作关系服务的业务员',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::id(),
                'description' => 'id',
            ],
            'cooperation_application_id'            => [
                'type'        => Type::int(),
                'description' => '合作关系id',
            ],
            'salesman_id'          => [
                'type'        => Type::int(),
                'description' => '为合作关系服务的业务员id',
            ],
        ];
    }
}
