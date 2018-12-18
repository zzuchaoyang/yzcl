<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceAdjustmentStatusToProductPriceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('product_price_logs','price_adjustment_status')){
            Schema::table('product_price_logs', function (Blueprint $table) {
                $table->string('price_adjustment_status',15)->index()->after('prices')->default('未审核')->comment('调价单状态');
            });
        }

        if(!Schema::hasColumns('product_price_adjustments',['canceler_id','cancel_at'])){
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->unsignedInteger('canceler_id')->index()->after('producer_id')->nullable()->comment('作废者ID');
                $table->timestamp('cancel_at')->after('examine_at')->nullable()->comment('作废时间');
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
        Schema::table('product_price_logs', function (Blueprint $table) {
            //
        });
    }
}
