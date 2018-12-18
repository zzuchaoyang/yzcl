<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignedAtIntoOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'signed_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('signed_at')->after('send_start_at')->nullable()->comment('订单签收时间');
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
