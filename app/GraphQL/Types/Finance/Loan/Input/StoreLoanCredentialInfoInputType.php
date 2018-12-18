<?php

namespace App\GraphQL\Types\Finance\Loan\Input;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreLoanCredentialInfoInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreLoanCredentialInfoInput',
        'description' => '贷款证件信息',
    ];

    public function fields()
    {
        return [
            'yyzz'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '营业执照',
            ],
            'sfz'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '法人代表/负责人身份证',
            ],
            'jhz'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '法人代表/负责人结婚证',
            ],
            'yhkhxkz'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '银行开户许可证',
            ],
            'gszc'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '公司章程',
            ],
            'mmzm'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '办公场地租赁/买卖证明',
            ],
            'grzxbg'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '个人征信报告',
            ],
            'qyzxbg'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '企业征信报告',
            ],
            'dlht'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '厂商经销/代理合同',
            ],
            'lsscght'            => [
                'type'        => Type::listOf(Type::string()),
                'description' => '零售商采购合同',
            ],
        ];
    }
}
