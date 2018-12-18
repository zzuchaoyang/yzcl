<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldIntoOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', ' supplier_name')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('supplier_name')->nullable()->after('supplier_id')->comment('供应商的名称');
                $table->string('supplier_mobile')->nullable()->after('supplier_name')->comment('供应商手机号');
                $table->string('customer_name')->nullable()->after('customer_id')->comment('零售店名称');
                $table->string('customer_mobile')->nullable()->after('customer_name')->comment('零售店手机号');
            });
        }

        if (!Schema::hasColumn('order_product', ' product_name')) {
            Schema::table('order_product', function (Blueprint $table) {
                $table->string('product_name')->nullable()->after('product_id')->comment('商品名字');
                $table->string('product_picture')->nullable()->after('product_name')->comment('商品封面图');
                $table->string('product_code')->nullable()->after('product_picture')->comment('商品条码');
                $table->string('brand_name')->nullable()->after('product_code')->comment('商品品牌名字');
                $table->string('product_order_unit')->nullable()->after('brand_name')->comment('商品订货单位');
                $table->string('product_specifications')->nullable()->after('product_order_unit')->comment('商品订货规格');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        //
    }
}
