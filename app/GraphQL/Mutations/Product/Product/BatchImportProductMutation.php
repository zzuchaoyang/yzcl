<?php

namespace App\GraphQL\Mutations\Product\Product;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Product\Product;
use App\Models\Product\ProductPriceLog;
use DB;
use GraphQL;
use GraphQL\Type\Definition\Type;

class BatchImportProductMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'batchImportProduct',
        'description' => '批量导入商品',
    ];

    public function type()
    {
        return GraphQL::type('BatchImportProduct');
    }

    public function args()
    {
        return [
            'data' => [
                'name'        => 'data',
                'type'        => Type::listOf(GraphQL::type('ProductInput')),
                'description' => '导入的商品数据',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $datas = $args['data'];
        if (is_array($datas) && count($datas)) {
            $errorData = [];
            $code = '';
            $format = '';
            foreach ($datas as $key=>$data) {
                // 检查商品条码是否存在，如果存在，则抛出异常，前端显示
                $query = Product::where('supplier_id', user()->id)->where('bar_code', $data['bar_code']);
                if ($query->exists()) {
                    $code .= ($key+1).",";
                    $errorData['code'] = $code;
                    continue;
                }

                $product = $this->createProduct($data, $errorData, $key);
                if (!$product) {
                    $format .= ($key+1).",";
                    $errorData['format'] = $format.',';
                    continue;
                }
            }
        }

        return $errorData;
    }

    private function createProduct($data)
    {
        return DB::transaction(function () use ($data) {
            // 创建
            $product = Product::create($data);
            // 更新品牌的商品统计
            $product->brand->syncBrandOfProducts($product->brand);
            //生成一个商品价格日志
            ProductPriceLog::syncPriceLog($product);

            return $product;
        });
    }
}
