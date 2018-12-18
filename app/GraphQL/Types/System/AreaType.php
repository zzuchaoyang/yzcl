<?php

namespace App\GraphQL\Types\System;

use App\GraphQL\Types\BaseType;
use App\Models\System\Area;
use GraphQL\Type\Definition\Type;

class AreaType extends BaseType
{
    protected $attributes = [
        'name'        => 'Area',
        'description' => '地区类型',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */
    // protected $inputObject = true;

    public function fields()
    {

        return [
            'id'          => [
                'type'        => Type::id(),
                'description' => 'ID',
            ],
            'name'        => [
                'type'        => Type::string(),
                'description' => '地区名称',
            ],
            'parten_id'   => [
                'type'        => Type::id(),
                'description' => '父级 ID',
            ],
            'level'       => [
                'type'        => Type::string(),
                'description' => '层级',
            ],
            'area_code'   => [
                'type'        => Type::string(),
                'description' => '区号',
            ],
            'zip_code'    => [
                'type'        => Type::string(),
                'description' => '邮政编码',
            ],
            'city_code'   => [
                'type'        => Type::string(),
                'description' => '区号',
            ],
            'short_name'  => [
                'type'        => Type::string(),
                'description' => '简称',
            ],
            'merger_name' => [
                'type'        => Type::string(),
                'description' => '组合名',
            ],
            'pinyin'      => [
                'type'        => Type::string(),
                'description' => '拼音',
            ],
            'lng'         => [
                'type'        => Type::string(),
                'description' => '经度',
            ],
            'lat'         => [
                'type'        => Type::string(),
                'description' => '纬度',
            ],
        ];
    }

    protected function resolveNameField($root, $args)
    {
        if (in_array($root->name, [ Area::NAME_TERRITORY, Area::NAME_MUNICIPAL ])) {
            return $root->short_name.'市';
        }

        if ($root->name == Area::NAME_COUNTY) {
            return $root->short_name;
        }

        return $root->name;
    }
}
