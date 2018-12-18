<?php

namespace App\Models\Supplier\Traits\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Supplier\Driver;
use App\Models\Supplier\SupplierOrg;
use DB;

trait SupplierOrgRepository
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
        // create root node
        if (!$supplierOrg = SupplierOrg::find(array_get($args, 'data.parent_id', 0))) {
            return self::create(array_get($args, 'data'));
        }

        // when tip_on ban add sub node
        if ($supplierOrg->is_tip == self::TIP_ON){
            throw new GeneralException("该 {$supplierOrg->name} 部门为末级部门 ，禁止添加子部门。");
        }

        // appending and prepending to the specified parent
        unset($args['data']['parent_id']);

        return self::create(array_get($args, 'data'), $supplierOrg);
    }

    /**
     * @param                $args
     *
     * @return mixed
     * @throws GeneralException
     * @throws \Throwable
     */
    public function updateOrg($args)
    {
        $args = $this->updateBeforeValidationData($args);

        return DB::transaction(function () use ($args) {

            $data = array_get($args, 'data');

            $status = array_get($data, 'status', true);

            // 更新状态 及 子集状态
            if ($status !== $this->status) {
                self::whereDescendantOrSelf($this)->each(function (SupplierOrg $org) use ($status) {
                    $org->update(compact('status'));
                });
            }

            return $this->update(array_except($data,'status')) ? self::find($this->id) : false;
        });
    }

    /**
     * @param $args
     *
     * @return mixed
     * @throws GeneralException
     */
    private function updateBeforeValidationData($args)
    {
        $parentId = array_get($args, 'data.parent_id', 0);

        // 所编辑的这条数据的父级 ID 不能是这条数据的本身
        if ($this->id == $parentId) {
            throw new GeneralException('操作错误 , 请正确选择所属关系 。');
        }

        // 所编辑的这条数据的父级 ID 不能是这条数据的后代
        $parentOrg = self::find($parentId);
        if ($parentOrg && $parentOrg->isDescendantOf($this)) {
            throw new GeneralException('操作错误 , 请正确选择所属关系 。');
        }

        // 为末级节点时仅允许添加人员
        if ($parentOrg && $parentOrg->is_tip == self::TIP_ON){
            throw new GeneralException("该 {$parentOrg->name} 部门为末级部门 ，禁止添加子部门。");
        }

        // 该部门下有子集时 禁止设置为末级
        if (array_get($args, 'data.is_tip') == self::TIP_ON && $this->children()->exists()) {
            throw new GeneralException('该部门下有子部门，禁止设置为末级');
        }

        // 该部门下有子集时 禁止将部门性质设置为人员
        //if (array_get($args, 'data.type') == self::TYPE_STAFF && $this->children()->exists()) {
        //    throw new GeneralException('该部门下有子部门，禁止将部门性质设置为人员');
        //}

        // 该部门下有人员时 禁止设置为 非末级
        if (array_get($args, 'data.is_tip') == self::TIP_OFF && Driver::where('supplier_org_id', $this->id)->exists()) {
            throw new GeneralException('该部门下有在职人员，禁止设置为非末级');
        }

        // 部门性质为人员时 禁止修改部门性质
        //if (array_get($args, 'data.type') != $this->type && self::TYPE_STAFF == $this->type) {
        //    throw new GeneralException('部门性质为人员时，禁止修改部门性质');
        //}

        if ($parentId == $this->parent_id && isset($args['data']['parent_id'])) {
            unset($args['data']['parent_id']);
        }

        return $args;
    }

}
