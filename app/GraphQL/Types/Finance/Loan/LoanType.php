<?php

namespace App\GraphQL\Types\Finance\Loan;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class LoanType extends BaseType
{
    protected $attributes = [
        'name'        => 'Loan',
        'description' => '贷款',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'id',
            ],
            'supplier_id'      => [
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'supplier'      => [
                'type'        => GraphQL::type('Supplier'),
                'description' => '供应商',
            ],
            'period'  => [
                'type'        => Type::string(),
                'description' => '贷款周期',
            ],
            'amount'         => [
                'type'        => Type::float(),
                'description' => '贷款金额',
            ],
            'credential_info'   => [
                'type'        => GraphQL::type('LoanCredentialInfo'),
                'description' => '证件信息',
            ],
            'created_at'           => [
                'type'        => Type::string(),
                'description' => ' 创建时间',
            ],
        ];
    }

    protected function resolveCreatedAtField($root, $args)
    {
        return (string)$root->created_at->toDateTimeString();
    }
}
