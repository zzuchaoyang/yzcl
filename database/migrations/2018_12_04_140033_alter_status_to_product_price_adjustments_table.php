<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusToProductPriceAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('product_price_adjustments','status')){
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if(!Schema::hasColumn('product_price_adjustments','status')){
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->string('status',15)->index()->after('number')->default('未审核')->comment('调价单状态');
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
