<?php

namespace App\GraphQL\Mutations\Product\Product;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Product\Product;
use GraphQL\Type\Definition\Type;

class DeleteProductMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'deleteProduct',
        'description' => '删除商品',
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'id' => [
                'name'        => 'id',
                'type'        => Type::nonNull(Type::id()),
                'description' => '用户 ID',
                'rules'       => ['required'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $product = Product::query()->find(array_get($args, 'id'));
        if (!$product) {
            throw new GeneralException('该数据已经不存在了');
        }

        return $product->delete();
    }
}
