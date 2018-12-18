<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIsLastStageToBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('brands', 'is_last_stage')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('is_last_stage');
            });
        }

        if(!Schema::hasColumn('brands','is_last_stage')){
            Schema::table('brands', function (Blueprint $table) {
                $table->boolean('is_last_stage')->default(0)->after('level')->comment('是否末级品牌 ');
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
