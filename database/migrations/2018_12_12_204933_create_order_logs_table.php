<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('order_logs')) {
            Schema::create('order_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('order_id')->index()->comment('订单id');
                $table->string('position')->nullable()->comment('当前位置');
                $table->string('latitude')->nullable()->comment('当前位置纬度');
                $table->string('longitude')->nullable()->comment('当前位置经度');
                $table->string('content')->nullable()->comment('详细内容');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('order_logs');
    }
}
