<?php

namespace App\GraphQL\Mutations\Product\Product;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Product\ProductPriceAdjustment;
use App\Models\Product\ProductPriceLog;
use DB;
use GraphQL;
use GraphQL\Type\Definition\Type;

class StoreProductPriceAdjustmentMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeProductPriceAdjustment',
        'description' => '创建、修改商品调价单',
    ];

    public function type()
    {
        return GraphQL::type('ProductPriceAdjustment');
    }

    public function args()
    {
        return [
            'data'  => [
                'name'        => 'data',
                'type'        => GraphQL::type('ProductPriceAdjustmentInput'),
                'description' => '商品调价单数据',
                'rules'       => ['required'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        //格式化价格字段
        $data = $this->formatProductOfPrice($args['data']);

        return DB::transaction(function () use ($data) {
            if (!array_has($data, 'id')) {
                $data['producer_id'] = user()->id;
                $productPriceAdjustment =  ProductPriceAdjustment::create($data);
                $this->getCode($productPriceAdjustment);
                $this->storePriceLog($productPriceAdjustment, $data);

                return $productPriceAdjustment;
            } else {
                $productPriceAdjustment = ProductPriceAdjustment::findOrFail($data['id']);
                if (isset($data['status']) && ProductPriceAdjustment::IS_EXAMINE == $data['status']) {
                    if (isset($data['effective_at']) && $data['effective_at']) {
                        $effectiveAt = $data['effective_at'];
                    } else {
                        $effectiveAt = $productPriceAdjustment->effective_at;
                    }
                    if (strtotime(now()) > strtotime($effectiveAt)) {
                        throw new GeneralException('您未在调价单生效时间之前审核，请先修改生效日期');
                    }
                    $data['author_id'] = user()->id;
                    $data['examine_at'] = now();
                }
                if (isset($data['status']) && ProductPriceAdjustment::IS_CANCEL == $data['status']) {
                    $data['canceler_id'] = user()->id;
                    $data['cancel_at'] = now();
                }
                $this->storePriceLog($productPriceAdjustment, $data);
                $productPriceAdjustment->update($data);

                return $productPriceAdjustment;
            }
        });
    }

    private function getCode(ProductPriceAdjustment $productPriceAdjustment)
    {
        $code = generateUuid('TJD', $productPriceAdjustment->id);

        return $productPriceAdjustment->update(['code'=>$code]);
    }

    private function storePriceLog($productPriceAdjustment, $data)
    {
        if (!array_has($data, 'id')) {
            if (is_array($data['products']) && count($data['products'])) {
                foreach ($data['products'] as $product) {
                    $logData['brand_id']                           = $product['brand_id'];
                    $logData['product_id']                         = $product['id'];
                    $logData['supplier_id']                        = $product['supplier_id'];
                    $logData['effective_at']                       = $productPriceAdjustment->effective_at;
                    $logData['product_price_adjustment_id']        = $productPriceAdjustment->id;
                    $logData['prices']                             = [
                        'one_price'   => $product['one_price'],
                        'two_price'   => $product['two_price'],
                        'three_price' => $product['three_price'],
                        'four_price'  => $product['four_price'],
                        'five_price'  => $product['five_price'],
                        'retail_price'=> $product['retail_price'],
                        'trade_price' => $product['trade_price'],
                    ];
                    ProductPriceLog::create($logData);
                }

                return true;
            }
        } else {
            if (isset($data['products']) && is_array($data['products']) && count($data['products'])) {
                ProductPriceLog::where('supplier_id', $productPriceAdjustment->supplier_id)->where('product_price_adjustment_id', $productPriceAdjustment->id)->delete();
                foreach ($data['products'] as $product) {
                    $prices       = [
                        'one_price'   => $product['one_price'],
                        'two_price'   => $product['two_price'],
                        'three_price' => $product['three_price'],
                        'four_price'  => $product['four_price'],
                        'five_price'  => $product['five_price'],
                        'retail_price'=> $product['retail_price'],
                        'trade_price' => $product['trade_price'],
                    ];
                    $priceLogData = [
                            'supplier_id'                => $productPriceAdjustment->supplier_id,
                            'brand_id'                   => $product['brand_id'],
                            'product_id'                 => $product['id'],
                            'product_price_adjustment_id'=> $productPriceAdjustment->id,
                            'prices'                     => $prices,
                            'price_adjustment_status'    => isset($data['status']) ? $data['status'] : $productPriceAdjustment->status,
                            'effective_at'               => $data['effective_at'],
                        ];
                    ProductPriceLog::create($priceLogData);
                }
            }
            if (isset($data['status']) && in_array($data['status'], [ProductPriceAdjustment::IS_EXAMINE, ProductPriceAdjustment::IS_CANCEL])) {
                ProductPriceLog::where('supplier_id', $productPriceAdjustment->supplier_id)->where('product_price_adjustment_id', $productPriceAdjustment->id)->update(['price_adjustment_status'=>$data['status']]);
            }
        }
    }

    private function formatProductOfPrice($data)
    {
        if (isset($data['products']) && is_array($data['products']) && count($data['products'])) {
            foreach ($data['products'] as &$product) {
                //检查前端传来的价格字段
                $this->checkInputOfPrice($product);
                $product['one_price']    =  sprintf('%.2f', $product['one_price']);
                $product['two_price']    = isset($product['two_price']) ? sprintf('%.2f', $product['two_price']) : null;
                $product['three_price']  = isset($product['three_price']) ? sprintf('%.2f', $product['three_price']) : null;
                $product['four_price']   = isset($product['four_price']) ? sprintf('%.2f', $product['four_price']) : null;
                $product['five_price']   = isset($product['five_price']) ? sprintf('%.2f', $product['five_price']) : null;
                $product['retail_price'] = sprintf('%.2f', $product['retail_price']);
                $product['trade_price']  = sprintf('%.2f', $product['trade_price']);
            }
        }

        return $data;
    }

    private function checkInputOfPrice($product)
    {
        if (!isset($product['one_price']) || !$product['one_price']) {
            throw new GeneralException('一级供货价不能为空');
        }
        if (!isset($product['retail_price']) || !$product['retail_price']) {
            throw new GeneralException('建议零售价不能为空');
        }
        if (!isset($product['trade_price']) || !$product['trade_price']) {
            throw new GeneralException('订货单位含税价不能为空');
        }
    }
}
