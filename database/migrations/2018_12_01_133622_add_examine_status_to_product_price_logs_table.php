<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExamineStatusToProductPriceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('product_price_logs', ['effective_at'])) {
            Schema::table('product_price_logs', function (Blueprint $table) {
                $table->timestamp('effective_at')->index()->nullable()->after('prices')->comment('调价单生效时间');
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
