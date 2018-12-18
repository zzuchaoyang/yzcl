<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPriceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id')->nullable()->index()->comment('供应商ID');
            $table->unsignedInteger('brand_id')->nullable()->index()->comment('商品所属品牌ID');
            $table->unsignedInteger('product_id')->index()->nullable()->comment('商品ID');
            $table->unsignedInteger('product_price_adjustment_id')->index()->nullable()->comment('商品调价单ID');
            $table->json('prices')->nullable()->comment('商品价格');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_price_logs');
    }
}
