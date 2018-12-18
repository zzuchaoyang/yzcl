<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('products')) {
            if(Schema::hasColumns('products',['one_supply_price','two_trade_price','three_trade_price','four_trade_price','five_trade_price'])){
                Schema::table('products', function (Blueprint $table) {
                    $table->renameColumn('one_supply_price', 'one_price');
                    $table->renameColumn('two_trade_price', 'two_price');
                    $table->renameColumn('three_trade_price', 'three_price');
                    $table->renameColumn('four_trade_price', 'four_price');
                    $table->renameColumn('five_trade_price', 'five_price');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
