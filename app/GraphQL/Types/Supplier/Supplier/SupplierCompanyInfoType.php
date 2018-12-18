<?php

namespace App\GraphQL\Types\Supplier\Supplier;

use App\GraphQL\Types\BaseType;
use App\Models\System\Area;
use GraphQL\Type\Definition\Type;

class SupplierCompanyInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'SupplierCompanyInfo',
        'description' => '公司信息',
    ];

    public function fields()
    {
        return [
            'gsmc'    => [
                'type'        => Type::string(),
                'description' => '公司名称',
            ],
            'gsxz'    => [
                'type'        => Type::string(),
                'description' => '工商性质',
            ],
            'frdb'    => [
                'type'        => Type::string(),
                'description' => '法人代表',
            ],
            'frlxdh'  => [
                'type'        => Type::string(),
                'description' => '法人联系电话',
            ],
            'gscz'    => [
                'type'        => Type::string(),
                'description' => '公司传真',
            ],
            'gsyx'    => [
                'type'        => Type::string(),
                'description' => '公司邮箱',
            ],
            'ywlxr'   => [
                'type'        => Type::string(),
                'description' => '业务联系人',
            ],
            'ywlxdh'  => [
                'type'        => Type::string(),
                'description' => '业务联系电话',
            ],
            'area_id' => [
                'type'        => Type::id(),
                'description' => '地区 ID',
            ],
            'parent_area_id' => [
                'type'        => Type::id(),
                'description' => '地区 ID',
            ],
            'parent_ids' => [
                'type'        => Type::listOf(Type::id()),
                'description' => '地区 ID',
            ],
            'merger_area_name' => [
                'type'        => Type::string(),
                'description' => '地区 ID',
            ],
            'gsdz'    => [
                'type'        => Type::string(),
                'description' => '公司地址',
            ],
            'xxzs'    => [
                'type'        => Type::string(),
                'description' => '形象展示',
            ],
            'gsjs'    => [
                'type'        => Type::string(),
                'description' => '公司介绍',
            ],
        ];
    }

    /**
     * 地区名称
     *
     * @param $root
     * @param $args
     *
     * @return int
     */
    protected function resolveParentAreaIdField($root, $args)
    {
        if (!isset($root['area_id'])) {
            return 0;
        }

        if (isset($root['area_id']) && $area = Area::find($root['area_id'])) {
            return $area->parent_id;
        }

        return 0;
    }

    /**
     * 父级链
     *
     * @param $root
     * @param $args
     *
     * @return array
     */
    protected function resolveParentIdsField($root, $args)
    {
        if (!isset($root['area_id'])) {
            return [];
        }

        if (isset($root['area_id']) && $area = Area::find($root['area_id'])) {
            return array_pluck(Area::ancestorsAndSelf($root['area_id']), 'id');
        }

        return [];
    }

    /**
     * 地区名称
     *
     * @param $root
     * @param $args
     *
     * @return string
     */
    protected function resolveMergerAreaNameField($root, $args)
    {
        $areaName = '';

        if (!isset($root['area_id'])) {
            return $areaName;
        }

        if (!$area = Area::find($root['area_id'])){
            return $areaName;
        }

        $this->getParentName($area,$areaName);

        return $areaName.$area->short_name ;
    }

    private function getParentName($area, &$areaName)
    {
        if ($area = $area->parent) {
            $areaName =  $area->short_name .'-'. $areaName;
            $this->getParentName($area, $areaName);
        }
    }

}
