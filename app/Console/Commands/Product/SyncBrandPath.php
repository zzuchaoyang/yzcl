<?php

use App\Models\Product\Brand;
use Illuminate\Console\Command;

class SyncBrandPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'product:update-product-price {product_id? : 商品ID}';
    protected $signature = 'brand:sync-brand-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '品牌 ：同步品牌组织结构路径';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Brand::whereIn('status', ['停用','正常'])->chunk(100, function ($brands) {
            $brands->each(function ($brand) {
                if (1 != $brand->level) {
                    if (2 == $brand->level) {
                        $path = $brand->pid.'-'.$brand->id.'-';
                    }
                    if (3 == $brand->level) {
                        $brandTwo = Brand::where('id', $brand->pid)->first();
                        $brandOne = Brand::where('id', $brandTwo->pid)->first();
                        $path = $brandOne->id.'-'.$brand->pid.'-'.$brand->id.'-';
                    }
                } else {
                    $path = $brand->id.'-';
                }
                $brand->update(['path'=>$path]);
            });
        });
        echo '同步品牌组织结构路径成功';
    }
}
