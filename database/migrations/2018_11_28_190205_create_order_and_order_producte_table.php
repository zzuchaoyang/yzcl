<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAndOrderProducteTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('orders')) {
            Schema::dropIfExists('orders');
        }
        if (Schema::hasTable('order_product')) {
            Schema::dropIfExists('order_product');
        }
        if (Schema::hasTable('order_products')) {
            Schema::dropIfExists('order_products');
        }

        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('supplier_id')->index()->comment('供应商id');
                $table->string('order_number')->nullable()->index()->comment('订单编号');
                $table->decimal('price', 15, 2)->nullable()->comment('订单金额');
                $table->unsignedInteger('number')->nullable()->comment('订单数量');
                $table->decimal('send_price', 15, 2)->nullable()->comment('发货订单金额');
                $table->unsignedInteger('send_number')->nullable()->comment('发出订单数量');
                $table->unsignedInteger('customer_id')->index()->comment('客户id');
                $table->unsignedInteger('salesman_id')->nullable()->index()->comment('业务员id');
                $table->timestamp('order_at')->nullable()->index()->comment('订单产生时间');
                $table->string('status')->nullable()->index()->comment('订单状态');
                $table->string('car_number')->nullable()->comment('车牌号');
                $table->unsignedInteger('driver_id')->nullable()->index()->comment('司机id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_product')) {
            Schema::create('order_product', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('order_id')->index()->comment('订单id');
                $table->unsignedInteger('product_id')->nullable()->index()->comment('商品id');
                $table->unsignedInteger('product_number')->nullable()->index()->comment('商品数量');
                $table->decimal('product_price', 15, 2)->nullable()->nullable()->comment('商品单价');
                $table->decimal('product_total_price', 15, 2)->nullable()->nullable()->comment('商品总价格');
                $table->unsignedInteger('send_number')->nullable()->comment('发出商品数量');
                $table->decimal('send_price', 15, 2)->nullable()->nullable()->comment('发出商品单价');
                $table->decimal('send_total_price', 15, 2)->nullable()->nullable()->comment('发出商品的总价格');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
