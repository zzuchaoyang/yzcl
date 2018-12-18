<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('products')) {
            if (Schema::hasColumns('products', ['one_category_id', 'two_category_id', 'three_category_id'])) {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropColumn('one_category_id');
                    $table->dropColumn('two_category_id');
                    $table->dropColumn('three_category_id');
                });
            }
            if(!Schema::hasColumns('products',['order_unit','single_num','trade_price','one_supply_price','two_trade_price','three_trade_price','four_trade_price','five_trade_price'])){
                Schema::table('products', function (Blueprint $table) {
                    $table->string('order_unit',15)->nullable()->after('check_pictures')->comment('订货单位');
                    $table->unsignedTinyInteger('single_num')->nullable()->after('check_pictures')->comment('单品数量');
                    $table->decimal('trade_price',8,2)->nullable()->after('check_pictures')->comment('批发含税价');
                    $table->decimal('one_price',8,2)->nullable()->after('check_pictures')->comment('一级供货价');
                    $table->decimal('two_price',8,2)->nullable()->after('check_pictures')->comment('二级供货价');
                    $table->decimal('three_price',8,2)->nullable()->after('check_pictures')->comment('三级供货价');
                    $table->decimal('four_price',8,2)->nullable()->after('check_pictures')->comment('四级供货价');
                    $table->decimal('five_price',8,2)->nullable()->after('check_pictures')->comment('五级供货价');
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
