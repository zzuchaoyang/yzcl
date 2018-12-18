<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('supplier_id')->index()->comment('供应商id');
                $table->string('order_number')->nullable()->index()->comment('订单编号');
                $table->decimal('price')->nullable()->comment('订单价格');
                $table->unsignedInteger('customer_id')->index()->comment('客户id');
                $table->unsignedInteger('salesman_id')->nullable()->index()->comment('业务员id');
                $table->timestamp('order_at')->nullable()->index()->comment('订单产生时间');
                $table->string('status')->nullable()->index()->comment('订单状态');
                $table->tinyInteger('is_error')->nullable()->comment('订单有误无误');
                $table->string('car_number')->nullable()->comment('车牌号');
                $table->unsignedInteger('driver_id')->nullable()->index()->comment('司机id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('orders')) {
            Schema::dropIfExists('orders');
        }
    }
}
