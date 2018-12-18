<?php

namespace App\GraphQL\Types\Supplier\Supplier\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreSupplierMutationInfoFinanceInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreSupplierMutationInfoFinanceInput',
        'description' => '供应商财务信息',
    ];

    public function fields()
    {
        return [
            'kpmc'   => [
                'type'        => Type::string(),
                'description' => '开票名称',
            ],
            'shxydm' => [
                'type'        => Type::string(),
                'description' => '社会信用代码',
            ],
            'zcdz'   => [
                'type'        => Type::string(),
                'description' => '注册地址',
            ],
            'lxdh'   => [
                'type'        => Type::string(),
                'description' => '联系电话',
            ],
            'khyh'   => [
                'type'        => Type::string(),
                'description' => '开户银行',
            ],
            'yhzh'   => [
                'type'        => Type::string(),
                'description' => '银行账户',
            ],
        ];
    }
}
