<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingToSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if(!Schema::hasColumn('suppliers','setting')){
                $table->json('setting')->after('loan_info')->nullable()->comment('供应商配置信息');
            }
            if(Schema::hasColumn('suppliers','price_increase_ratio')){
                $table->dropColumn('price_increase_ratio');
            }
        });
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
