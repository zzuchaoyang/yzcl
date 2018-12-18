<?php

namespace App\GraphQL\Queries\Common;

use App\GraphQL\Queries\BaseQuery;
use GraphQL;
use GraphQL\Type\Definition\Type;

class OptionsQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'options',
        'description' => '选项内容',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Option'));
    }

    public function args()
    {
        return [
            'category' => [
                'type'        => Type::string(),
                'description' => '分类',
            ],
            'type'     => [
                'type'        => Type::string(),
                'description' => '类型',
            ],
        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        // 只返回 category
        if (!array_get($args, 'category') && !array_get($args, 'type') && config('app.env') !== 'production') {
            $options = $this->getOptions();
            $options = $options->map(function ($option) {
                return [
                    'name'  => $option['description'],
                    'value' => $option['category'],
                ];
            });

            return $options;
        }

        // 返回 category 下面的 types
        if (array_get($args, 'category') && !array_get($args, 'type') && config('app.env') !== 'production') {
            $options = $this->getOptions()->where('category', array_get($args, 'category'))->first();

            if (!$options) {
                return [];
            }

            $options = collect($options['types'])->map(function ($option) {
                return [
                    'name'  => $option['description'],
                    'value' => $option['type'],
                ];
            });

            return $options;
        }

        // 返回 category -> types 下面的具体可选项
        if (array_get($args, 'category') && array_get($args, 'type')) {
            $options = $this->getOptions()->where('category', array_get($args, 'category'))->first();

            if (!$options) {
                return [];
            }

            $options = collect($options['types'])->where('type', array_get($args, 'type'))->first();

            if (!$options) {
                return [];
            }

            return collect($options['options'])->map(function ($option) {
                return [
                    'name'  => $option['name'],
                    'value' => array_get($option, 'value', array_get($option, 'name')),
                ];
            });
        }

        return [];
    }

    private function getOptions()
    {
        return collect($this->options);
    }

    private $options = [
        [
            'category'    => 'order',
            'description' => '订单',
            'types'       => [
                [
                    'type'        => 'status',
                    'description' => '订单状态',
                    'options'     => [
                        [
                            'name'  => '新建',
                            'value' => 'new',
                        ],
                        [
                            'name'  => '待审核',
                            'value' => 'approving',
                        ],
                        [
                            'name'  => '无法供货',
                            'value' => 'unsupply',
                        ],
                        [
                            'name'  => '待确认',
                            'value' => 'confirming',
                        ],
                        [
                            'name'  => '取消订货',
                            'value' => 'cancelled',
                        ],
                        [
                            'name'  => '待发货',
                            'value' => 'shipping',
                        ],
                        [
                            'name'  => '待签收',
                            'value' => 'signing',
                        ],
                        [
                            'name'  => '强制签收',
                            'value' => 'force_signed',
                        ],
                        [
                            'name'  => '已签收（无误）',
                            'value' => 'signed',
                        ],
                        [
                            'name'  => '已签收（有误）',
                            'value' => 'invalid_signed',
                        ],
                    ],
                ],
            ],
        ],
        [
            'category'    => 'cooperation',
            'description' => '合作关系',
            'types'       => [
            [
                'type'        => 'status',
                'description' => '合作状态',
                'options'     => [
                    [
                        'name'  => '正常合作',
                        'value' => 'normal',
                    ],
                    [
                        'name'  => '等待合作',
                        'value' => 'wait',
                    ],
                    [
                        'name'  => '拒绝合作',
                        'value' => 'reject',
                    ],
                    [
                        'name'  => '暂停合作',
                        'value' => 'pause',
                    ],
                ],
            ],
        ],
    ],
    [
        'category'    => 'grade',
        'description' => '供货价级别',
        'types'       => [
            [
                'type'        => 'supply_grade',
                'description' => '合作状态',
                'options'     => [
                    [
                        'name'  => '一级',
                        'value' => 'one',
                    ],
                    [
                        'name'  => '二级',
                        'value' => 'two',
                    ],
                    [
                        'name'  => '三级',
                        'value' => 'three',
                    ],
                    [
                        'name'  => '四级',
                        'value' => 'four',
                    ],
                    [
                        'name'  => '五级',
                        'value' => 'five',
                    ],
                ],
            ],
        ],
    ],
    ];
}
