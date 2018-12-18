<?php

namespace App\Models\Supplier\Traits\Repositories;

use App\Exceptions\GeneralException;
use App\Fx\Avatar\AvatarService;
use App\Models\Supplier\Driver;
use App\Models\Supplier\SupplierOrg;

trait DriverRepository
{
    /**
     * create
     *
     * @param $args
     *
     * @return mixed
     * @throws \Throwable
     */
    public static function store($args)
    {
        $data = $args['data'];

        if (self::where('mobile', $data['mobile'])->exists()) {
            throw new GeneralException('该用户已存在');
        }

        $driver = new Driver();

        $attributes = [
            'supplier_id' => user()->id ?? 1,
            'avatar'      => ( new AvatarService() )->put(array_get($data, 'name', '头像'))
        ];

        $driver->fill(array_merge($data, $attributes));

        if ($password = array_get($args, 'data.password')) {
            $driver->password = bcrypt($password);
        }

        $bool = $driver->save();

        if (!$bool){
            throw new GeneralException('创建失败。');
        }

        return Driver::where('mobile',$data['mobile'])->first();
    }

    /**
     * @param        $args
     *
     * @return Driver|Driver[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws GeneralException
     */
    public function updateDriver($args)
    {
        $args = $this->updateBeforeValidationData($args);

        $this->fill(array_get($args, 'data'));

        if (array_has($args, 'data.password')) {
            $this->password = bcrypt(array_get($args, 'data.password'));
        }

        $bool = $this->save();

        if (!$bool){
            throw new GeneralException('更新失败。');
        }

        return Driver::find($this->id);
    }

    /**
     * Update before validation data
     *
     * @param $args
     *
     * @return mixed
     * @throws GeneralException
     */
    private function updateBeforeValidationData($args)
    {
        if (user() instanceof Driver && user()->id != $this->id) {
            throw new GeneralException('更新失败，信息异常。');
        }

        if (array_has($args, 'data.mobile') && array_get($args, 'data.mobile') == $this->mobile) {
            unset($args['data']['mobile']);
        }

        if (array_has($args, 'data.mobile') && self::where('mobile', array_get($args, 'data.mobile'))->exists()) {
            throw new GeneralException('更新失败，手机号已存在。');
        }

        if (array_get($args, 'data.supplier_org_id') == $this->supplier_org_id) {
            unset($args['data']['supplier_org_id']);
        }

        if (array_has($args, 'data.supplier_org_id')) {
            $supplierOrg = SupplierOrg::find(array_get($args, 'data.supplier_org_id'));

            if ($supplierOrg && $supplierOrg->is_tip == SupplierOrg::TIP_OFF) {
                throw new GeneralException('更新失败，请在末级部门添加员工。');
            }
        }

        return $args;
    }

}
