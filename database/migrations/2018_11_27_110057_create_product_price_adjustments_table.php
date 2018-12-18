<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPriceAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id')->index()->nullable()->comment('供应商ID');
            $table->json('products')->nullable()->comment('商品调价清单');
            $table->string('code',50)->nullable()->comment('调价单编码');
            $table->timestamp('effective_at')->nullable()->comment('生效时间');
            $table->unsignedSmallInteger('number')->nullable()->comment('调价商品数量');
            $table->string('status')->nullable()->comment('调价单状态');
            $table->unsignedInteger('author_id')->index()->nullable()->comment('审核人ID');
            $table->timestamp('examine_at')->nullable()->comment('审核时间');
            $table->unsignedInteger('producer_id')->index()->nullable()->comment('制单人ID');
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
        Schema::dropIfExists('product_price_adjustments');
    }
}
