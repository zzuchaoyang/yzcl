<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductOfPriceToProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('retail_price', '14', '2')->change();
            $table->decimal('one_price', '14', '2')->change();
            $table->decimal('two_price', '14', '2')->change();
            $table->decimal('three_price', '14', '2')->change();
            $table->decimal('four_price', '14', '2')->change();
            $table->decimal('five_price', '14', '2')->change();
            $table->decimal('trade_price', '14', '2')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
