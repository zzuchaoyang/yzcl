<?php

use App\Models\Product\Brand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SyncPathToBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            //
        });
    }
}
