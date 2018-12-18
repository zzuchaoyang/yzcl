<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnToProductPriceAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasColumn('product_price_adjustments', 'product_id')) {
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }

        if (!Schema::hasColumn('product_price_adjustments', 'supplier_id')) {
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->unsignedInteger('supplier_id')->index()->nullable()->after('id')->comment('供应商ID');
            });
        }

        if (!Schema::hasColumn('products', 'supplier_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedInteger('supplier_id')->index()->nullable()->after('id')->comment('供应商ID');
            });
        }

        if (!Schema::hasColumn('product_price_adjustments', 'products')) {
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->json('products')->nullable()->after('supplier_id')->comment('调价单商品信息');
            });
        }

        if (!Schema::hasColumn('product_price_adjustments', 'examine_at')) {
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->timestamp('examine_at')->nullable()->after('author_id')->comment('审核时间');
            });
        }

        if (!Schema::hasColumn('product_price_adjustments', 'producer_id')) {
            Schema::table('product_price_adjustments', function (Blueprint $table) {
                $table->unsignedInteger('producer_id')->index()->nullable()->after('author_id')->comment('制单人ID');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('product_price_adjustments', function (Blueprint $table) {
            //
        });
    }
}
