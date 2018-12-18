<?php

use App\Models\Product\Product;
use App\Models\Product\ProductPriceAdjustment;
use App\Models\Product\ProductPriceLog;
use Illuminate\Console\Command;

class UpdateProductPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'product:update-product-price {product_id? : 商品ID}';
    protected $signature = 'product:update-product-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '供应商 ：更新商品的供货价格';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        return DB::transaction(function (){
            ProductPriceAdjustment::where('status',ProductPriceAdjustment::IS_EXAMINE)->where('is_sync',ProductPriceAdjustment::NO_SYNC)->where('effective_at', '<=', now())->orderBy('id', 'ASC')->chunk(100,function ($productPriceAdjustments){
                $productPriceAdjustments->map(function($productPriceAdjustment){
                    $products = $productPriceAdjustment->products;
                    if(is_array($products) && count($products)){
                        foreach($products as $val){
                            $query = ProductPriceLog::query();
                            if (isset($val['supplier_id'])){
                                $query = $query->where('supplier_id',$val['supplier_id']);
                            }
                            $priceLog = $query->where('product_id', $val['id'])->where('price_adjustment_status', '已审核')->where('product_price_adjustment_id',$productPriceAdjustment->id)->where('effective_at', '<=', now())->orderBy('id', 'DESC')->first();
                            if(isset($priceLog->prices) && $priceLog->prices){
                                Product::find($val['id'])->update($priceLog->prices);
                            }
                        }
                        $productPriceAdjustment->update(['is_sync'=>ProductPriceAdjustment::IS_SYNC,'sync_at'=>now()]);
                    }
                });
            });
            echo "商品价格同步成功";
        });
    }
}