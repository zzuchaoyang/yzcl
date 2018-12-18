<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('order_products')) {
            Schema::create('order_products', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('order_id')->index()->comment('订单id');
                $table->unsignedInteger('product_id')->nullable()->index()->comment('商品id');
                $table->unsignedInteger('product_number')->nullable()->index()->comment('商品数量');
                $table->decimal('product_price')->nullable()->nullable()->comment('商品总价格');
                $table->unsignedInteger('send_number')->nullable()->comment('发出商品数量');
                $table->decimal('send_order_price')->nullable()->nullable()->comment('发出订单价格');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('order_products')) {
            Schema::dropIfExists('order_products');
        }
    }
}
