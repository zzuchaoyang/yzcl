<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsTopToInnerMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('inner_messages', 'is_top')) {
            Schema::table('inner_messages', function (Blueprint $table) {
                $table->tinyInteger('is_top')->default(0)->after('is_read')->comment('是否置顶显示');
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
        Schema::table('inner_message', function (Blueprint $table) {
            //
        });
    }
}
