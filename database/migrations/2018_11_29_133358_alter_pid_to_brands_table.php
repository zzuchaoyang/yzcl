<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPidToBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('brands', 'pid')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('pid');
            });
        }

        if(!Schema::hasColumn('brands','pid')){
            Schema::table('brands', function (Blueprint $table) {
                $table->unsignedInteger('pid')->index()->default(0)->after('supplier_id')->comment('父级品牌ID');
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
        Schema::table('brands', function (Blueprint $table) {
            //
        });
    }
}
