<?php

namespace App\GraphQL\Mutations\Supplier\Driver\Auth;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Driver;
use App\Models\Supplier\SupplierOrg;
use Exception;
use GraphQL\Type\Definition\Type;

class DriverLoginMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'driverLogin',
        'description' => '供应商司机登录',
    ];

    public function type()
    {
        return Type::string();
    }

    public function args()
    {
        return [
            'mobile'      => [
                'name'  => 'mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => ['required'],
            ],
            'password'    => [
                'name'  => 'password',
                'type'  => Type::nonNull(Type::string()),
                'rules' => ['required', 'string'],
            ],
            'remember_me' => [
                'name'  => 'remember_me',
                'type'  => Type::boolean(),
                'rules' => ['boolean'],
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @return bool
     * @throws Exception
     */
    public function resolve($root, $args)
    {
        if (!$driver = Driver::query()->where('mobile', array_get($args, 'mobile'))->first()){
            throw new Exception('请提醒供应商开户!');
        }

        // 部门不存在
        if (!$org = SupplierOrg::find($driver->supplier_org_id)) {
            throw new Exception('部门信息异常，登录失败!');
        }

        // 部门被禁用
        if (!$org->status){
            throw new Exception('部门已被禁用，登录失败!');
        }

        // 人员被禁用
        if (!$driver->status){
            throw new Exception('账号已被停用，登录失败!');
        }

        $token = auth('supplier_driver')->attempt(array_only($args,['mobile','password']));

        if (!$token) {
            throw new Exception('账号或密码错误!');
        }

        return $token;
    }
}
