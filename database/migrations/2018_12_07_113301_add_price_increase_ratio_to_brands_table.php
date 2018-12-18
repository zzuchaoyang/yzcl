<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceIncreaseRatioToBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('suppliers','setting')){
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('setting');
            });
        }
        if(!Schema::hasColumn('brands','price_increase_ratio')){
            Schema::table('brands', function (Blueprint $table) {
                $table->json('price_increase_ratio')->after('logo_pic')->nullable()->comment('阶梯定价上浮比例配置');
            });
        }
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
