<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertUniqueKeyToProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasColumns('products', ['supplier_id', 'bar_code'])) {
            Schema::table('products', function (Blueprint $table) {
                $table->unique(['supplier_id','bar_code'])->comment('联合唯一索引');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_bar_code_unique');
        });
    }
}
