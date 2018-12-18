<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('customer_supplier')) {
            Schema::create('customer_supplier', function (Blueprint $table) {
                $table->unsignedInteger('supplier_id')->index()->comment('供应商id');
                $table->unsignedInteger('customer_id')->index()->comment('客户id');
                $table->string('status')->index()->comment('合作状态');
                $table->string('supply_grade')->nullable()->comment('供货价级别');
                $table->json('salesmen_ids')->comment('服务的业务员id');
                $table->timestamps();
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
        Schema::dropIfExists('customer_supplier');
    }
}
