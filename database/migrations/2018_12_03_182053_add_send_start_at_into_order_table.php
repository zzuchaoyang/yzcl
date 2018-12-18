<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendStartAtIntoOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'send_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('send_at')->after('driver_id')->nullable()->comment('订单的送达时间');
            });
        }
        if (!Schema::hasColumn('orders', 'send_start_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('send_start_at')->after('driver_id')->nullable()->comment('订单开始配送时间');
            });
        }

        if (!Schema::hasColumn('orders', 'position_info')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->json('position_info')->after('send_start_at')->nullable()->comment('订单送货信息');
            });
        }

        if (!Schema::hasColumn('orders', 'send_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('send_status')->after('position_info')->nullable()->comment('订单送货状态');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        //
    }
}
