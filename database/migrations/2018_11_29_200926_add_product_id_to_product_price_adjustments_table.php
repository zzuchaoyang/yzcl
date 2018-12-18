<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductIdToProductPriceAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('product_price_adjustments', 'product_id')) {
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }

        if(!Schema::hasColumn('product_price_adjustments','product_id')){
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->unsignedInteger('product_id')->index()->nullable()->after('id')->comment('商品ID');
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
