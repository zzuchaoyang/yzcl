<?php

namespace App\GraphQL\Mutations\Product\Brand;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use DB;
use GraphQL;
use GraphQL\Type\Definition\Type;

class StoreBrandMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeBrand',
        'description' => '创建、修改品牌',
    ];

    public function type()
    {
        return GraphQL::type('Brand');
    }

    public function args()
    {
        return [
            'data'  => [
                'name'        => 'data',
                'type'        => GraphQL::type('BrandInput'),
                'description' => '品牌数据',
                'rules'       => ['required'],
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     *
     * @return Brand|Brand[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     *
     * @throws GeneralException
     */
    public function resolve($root, $args)
    {
        return DB::transaction(function() use($args){
            $data = $this->formatPriceRatio($args['data']);

            $brandId   = array_get($data, 'id');
            $this->checkBrand($data, $brandId);
            if (!array_has($data, 'id')) {
                $brand = Brand::create($data);
                if(isset($data['pid']) && $data['pid']){
                    $parentBrand = Brand::where('id',$data['pid'])->first();
                    $data['path']=$parentBrand->path.$brand->id."-";
                }else{
                    $data['path'] = $brand->id."-";
                }
                $brand->update(['path'=>$data['path']]);

                return $brand;
            } else {
                $brand      = Brand::findOrFail($data['id']);
                $exceptKey  = ['supplier_id', 'quantity'];
                $updateData = array_except($data, $exceptKey);
                $brand->update($updateData);
                if(isset($data['status']) && $data['status']){
                    $this->syncBrandAndProductStatus($brand,$data['status']);
                }

                return $brand;
            }
        });
    }

    private function checkBrand($data, $brandId = null)
    {
        if (!isset($data['name']) || !$data['name']) {
            throw new GeneralException('品牌名称不能为空');
        }

        if (isset($data['is_last_stage']) && isset($data['id'])) {
            $childrenBrands = Brand::where('pid', $data['id'])->where('status', Brand::STATUS_ENABLED)->get();
            if (Brand::IS_LAST_STAGE == $data['is_last_stage'] && $childrenBrands->isNotEmpty()) {
                throw new GeneralException('该品牌有子品牌，不能设定为末级品牌');
            }
        }
        
        if (isset($data['is_last_stage']) && isset($data['price_increase_ratio'])) {
            unset($data['price_increase_ratio']);
        }
    }

    private function syncBrandAndProductStatus(Brand $brand,$status){
        //同步品牌状态
        $brand->syncChildrenBrandStatus($brand,$status);
        //同步品牌下商品状态
        $brand->syncBrandOfProductStatus($brand,$status);
    }

    private function formatPriceRatio($data)
    {
        if(isset($data['price_increase_ratio']) && $data['price_increase_ratio']){
            $data['one_increase_ratio']    = isset($data['one_increase_ratio']) ? sprintf('%.2f', $data['one_increase_ratio']) : null;
            $data['two_increase_ratio']    = isset($data['two_increase_ratio']) ? sprintf('%.2f', $data['two_increase_ratio']) : null;
            $data['three_increase_ratio']  = isset($data['three_increase_ratio']) ? sprintf('%.2f', $data['three_increase_ratio']) : null;
            $data['four_increase_ratio']   = isset($data['four_increase_ratio']) ? sprintf('%.2f', $data['four_increase_ratio']) : null;
            $data['five_increase_ratio']   = isset($data['five_increase_ratio']) ? sprintf('%.2f', $data['five_increase_ratio']) : null;
        }

        return $data;
    }
}
