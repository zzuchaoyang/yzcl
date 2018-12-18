<?php

namespace App\GraphQL\Mutations\Product\Product;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Product\Product;
use App\Models\Product\ProductPriceAdjustment;
use App\Models\Product\ProductPriceLog;
use GraphQL;

class StoreProductMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeProduct',
        'description' => '创建、修改商品',
    ];

    public function type()
    {
        return GraphQL::type('Product');
    }

    public function args()
    {
        return [
            'data' => [
                'name'        => 'data',
                'type'        => GraphQL::type('ProductInput'),
                'description' => '商品数据',
                'rules'       => ['required'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        //格式化价格
        $data = $this->formatProductPrice($args['data']);
        $id   = array_get($data, 'id');
        // 检查商品条码是否存在，如果存在，则抛出异常，前端显示
        Product::checkExistBarCode($data['bar_code'], $id);
        if ($id) {
            // 更新商品
            $product = Product::findOrFail($id);
            $this->updateProduct($product, $data);
        } else {
            // 新增商品
            $product = $this->createProduct($data);
        }

        return $product;
    }

    /**
     * @param $id
     * @param $data
     *
     * @return Product|Product[]|bool|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private function createProduct($data)
    {
        // 创建
        $product = Product::create($data);
        // 更新品牌的商品统计
        $product->brand->syncBrandOfProducts($product->brand);
        //生成一个商品价格日志
        ProductPriceLog::syncPriceLog($product);

        return $product;
    }

    /**
     * 更新商品
     *
     * @param Product $product
     * @param $data
     */
    private function updateProduct(Product $product, $data)
    {
        // 更新商品
        $exceptKey = ['supplier_id', 'brand_id', 'bar_code'];
        if ($data['single_num'] != $product->single_num) {
            $productPriceLogs = ProductPriceLog::where('supplier_id', $data['supplier_id'])->where('price_adjustment_status', ProductPriceAdjustment::NO_EXAMINE)->where('product_id', $product->id)->get();
            if ($productPriceLogs->isNotEmpty()) {
                throw new GeneralException('该商品存在未审核的调价单，不能修改包含单品数量');
            }
            $newProductPriceLog = ProductPriceLog::where('supplier_id', $data['supplier_id'])->where('price_adjustment_status', ProductPriceAdjustment::IS_EXAMINE)->where('product_id', $product->id)->orderBy('effective_at', 'DESC')->orderBy('id', 'DESC')->first();
            if ($newProductPriceLog && strtotime($newProductPriceLog->effective_at) > strtotime(now())) {
                throw new GeneralException('该商品存在已审核且未生效的调价单，不能修改包含单品数量');
            }
            //修改包含单品数量,要重新记录一条价格价格日志
            $product->one_price    = $data['one_price'];
            $product->two_price    = $data['two_price'];
            $product->three_price  = $data['three_price'];
            $product->four_price   = $data['four_price'];
            $product->five_price   = $data['five_price'];
            $product->retail_price = $data['retail_price'];
            $product->trade_price  = $data['trade_price'];
            ProductPriceLog::syncPriceLog($product);
        }
        $updateData = array_except($data, $exceptKey);
        $product->update($updateData);
    }

    private function formatProductPrice($data)
    {
        $data['one_price']    = isset($data['one_price']) ? sprintf('%.2f', $data['one_price']) : null;
        $data['two_price']    = isset($data['two_price']) ? sprintf('%.2f', $data['two_price']) : null;
        $data['three_price']  = isset($data['three_price']) ? sprintf('%.2f', $data['three_price']) : null;
        $data['four_price']   = isset($data['four_price']) ? sprintf('%.2f', $data['four_price']) : null;
        $data['five_price']   = isset($data['five_price']) ? sprintf('%.2f', $data['five_price']) : null;
        $data['retail_price'] = isset($data['retail_price']) ? sprintf('%.2f', $data['retail_price']) : null;
        $data['trade_price']  = isset($data['trade_price']) ? sprintf('%.2f', $data['trade_price']) : null;

        return $data;
    }
}
