<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id')->nullable()->index()->comment('供应商ID');
            $table->string('name',45)->nullable()->comment('品牌名称');
            $table->unsignedInteger('pid')->default(0)->comment('父级品牌ID');
            $table->unsignedTinyInteger('level')->nullable()->comment('分类层级');
            $table->boolean('is_last_stage')->default(0)->comment('是否末级品牌 ');
            $table->unsignedInteger('quantity')->default(0)->comment('品牌下的商品数量');
            $table->string('manufacturer')->nullable()->comment('生产厂商');
            $table->string('status')->index()->default('正常')->comment('品牌状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}
