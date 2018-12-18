<?php

namespace App\GraphQL\Mutations\Order;

use App\GraphQL\Mutations\BaseMutation;
use Cache;
use GraphQL;
use GraphQL\Type\Definition\Type;
use App\Models\Customer\Customer;

class ShoppingCartMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'shoppingCart',
        'description' => '购物车的储存',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('ShoppingCard'));
    }

    public function args()
    {
        return [
            'type' => [
                'name'        => 'type',
                'type'        => Type::string(),
                'description' => '操作类型',
                'rules'       => ['in:get,put'],
            ],
            'data' => [
                'name'        => 'data',
                'type'        => Type::listOf(GraphQL::type('ShoppingCardMutationInput')),
                'description' => '订单信息',
                'rules'       => 'required',
                ],
            ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        $user = user();

        return true;

        return  $user instanceof Customer;
    }

    public function resolve($root, $args)
    {
        $type = array_get($args, 'type');
        $data = array_get($args, 'data');
        //取值
        if ($type == 'get') {

        }
        //存到缓存
        else {

        }

        return $data;
    }
}
