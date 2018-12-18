<?php
namespace App\GraphQL\Mutations\Finance\Loan;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Finance\Loan;
use App\Models\Supplier\Supplier;
use GraphQL;
use Laravel\Passport\Bridge\User;

class StoreLoanMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeLoan',
        'description' => '创建、修改贷款',
    ];

    public function type()
    {
        return GraphQL::type('Loan');
    }

    public function args()
    {
        return [
            'data'  => [
                'name'        => 'data',
                'type'        => GraphQL::type('StoreLoanInput'),
                'description' => '贷款信息',
                'rules'       => ['required'],
            ],
        ];
    }

    /**
     *
     *
     * @param $root
     * @param $args
     * @param $currentUser
     *
     * @return bool
     */
    public function authenticated($root, $args, $currentUser)
    {
        return \user() instanceof Supplier;
    }

    public function resolve($root, $args)
    {
        $data = $args['data'];

        if (!array_has($data, 'id')) {
            $args['data']['supplier_id'] = user()->id;
            return Loan::create($args['data']);
        }

        $loan = Loan::findOrFail($data['id']);

        $loan->update(array_except($data, ['supplier_id']));

        return $loan;
    }
}
