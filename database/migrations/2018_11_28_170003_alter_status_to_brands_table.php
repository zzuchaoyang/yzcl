<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusToBrandsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasColumn('brands', 'status')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if(!Schema::hasColumn('brands','status')){
            Schema::table('brands', function (Blueprint $table) {
                $table->string('status')->index()->after('manufacturer')->default('正常')->comment('品牌状态');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            //
        });
    }
}
