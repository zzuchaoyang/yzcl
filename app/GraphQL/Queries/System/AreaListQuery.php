<?php

namespace App\GraphQL\Queries\System;

use App\GraphQL\Queries\BaseQuery;
use App\Models\System\Area;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use MatrixLab\LaravelAdvancedSearch\When;

class AreaListQuery extends BaseQuery
{

    protected $attributes = [
        'name'        => 'areaList',
        'description' => '地区列表',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Area'));
    }

    public function args()
    {
        return [
            'ids' => [
                'name'        => 'ids',
                'type'        => Type::listOf(Type::int()),
                'description' => 'ids 搜索',
            ],
            'parent_id' => [
                'name'        => 'parent_id',
                'type'        => Type::int(),
                'description' => '父级 搜索',
            ],
            'name'      => [
                'name'        => 'name',
                'type'        => Type::string(),
                'description' => '名称 搜索',
            ],
            'level'     => [
                'name'        => 'level',
                'type'        => Type::int(),
                'description' => '层级 搜索',
            ],
            'offset'    => [
                'name'        => 'offset',
                'type'        => Type::int(),
                'description' => '跳过的数量',
            ],
            'limit'     => [
                'name'        => 'limit',
                'type'        => Type::int(),
                'description' => '返回数量',
            ],
        ];
    }

    protected function wheres()
    {
        return [
            When::make($this->getInputArgs('ids') && !empty($this->getInputArgs('ids')))->success(function ($q) {
                $q->whereIn('id', $this->getInputArgs('ids'));
            }),
            'parent_id',
            'level',
            $this->queryName(),
        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        array_set($args, 'paginator.sorts', [
            '+level',
            '+id',
        ]);

        $areas = Area::getList($this->appendConditions(array_only($args, ['limit', 'offset']))->getConditions($args));

        // 跳过 市辖区
        $areas->each(function (Area $area,$key) use($areas){
            if($area->name == Area::NAME_MUNICIPAL){
                $areas->forget($key);
            }
        });

        return $areas;
    }

    /**
     * 模糊搜索
     *
     * @return When
     */
    protected function queryName(): When
    {
        return When::make($this->getInputArgs('name'))->success(function (Builder $builder) {
            $builder->where(function (Builder $q) {
                $name = $this->getInputArgs('name');
                $q->where('name', 'like', "{$name}%")->orWhere('pinyin', 'like', "{$name}%");
            });
        });
    }
}
