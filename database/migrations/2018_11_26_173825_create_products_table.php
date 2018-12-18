<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id')->index()->nullable()->comment('品牌ID');
            $table->string('status')->index()->default('启用')->comment('商品状态');
            $table->string('name',45)->nullable()->comment('商品名称');
            $table->string('bar_code',50)->nullable()->comment('商品条码');
            $table->string('unit',15)->nullable()->comment('商品单位');
            $table->unsignedInteger('spec_number')->nullable()->comment('规格数量');
            $table->string('spec_unit',15)->nullable()->comment('规格单位');
            $table->decimal('retail_price',8,2)->nullable()->comment('建议零售价');
            $table->string('make_place',50)->nullable()->comment('产地');
            $table->string('manufacturer',25)->nullable()->comment('生产厂商');
            $table->json('pictures')->nullable()->comment('商品图片');
            $table->json('check_pictures')->nullable()->comment('商品检测图片');
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
        Schema::dropIfExists('products');
    }
}
