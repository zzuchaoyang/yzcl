<?php

namespace App\GraphQL\Types\Finance\Loan\Input;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreLoanInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreLoanInput',
        'description' => '贷款保存信息',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::int(),
                'description' => 'id',
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
                'type'        => GraphQL::type('StoreLoanCredentialInfoInput'),
                'description' => '证件信息',
            ],
        ];
    }
}
