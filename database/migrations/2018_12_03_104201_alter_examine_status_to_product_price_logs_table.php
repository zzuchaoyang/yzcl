<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExamineStatusToProductPriceLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasColumn('product_price_logs', 'examine_status')) {
            Schema::table('product_price_logs', function (Blueprint $table) {
                $table->dropColumn('examine_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('product_price_logs', function (Blueprint $table) {
            //
        });
    }
}
