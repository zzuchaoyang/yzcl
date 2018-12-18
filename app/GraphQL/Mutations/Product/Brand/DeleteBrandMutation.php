<?php

namespace App\GraphQL\Mutations\Product\Brand;


use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Product\Brand;
use GraphQL\Type\Definition\Type;

class DeleteBrandMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'deleteBrand',
        'description' => '删除品牌',
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
        $brand = Brand::query()->find(array_get($args, 'id'));
        if (!$brand) {
            throw new GeneralException('该数据已经不存在了');
        }

        return $brand->delete();
    }

}