<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSyncToProductPriceAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('is_sync',['is_sync','sync_at'])){
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->tinyInteger('is_sync')->index()->default(0)->after('canceler_id')->comment('是否同步商品价格 0:否；1:是');
                $table->timestamp('sync_at')->nullable()->after('cancel_at')->comment('调价单同步商品价格时间');
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
        Schema::table('product_price_adjustments', function (Blueprint $table) {
            //
        });
    }
}
