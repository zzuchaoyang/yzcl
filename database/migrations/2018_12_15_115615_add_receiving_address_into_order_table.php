<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceivingAddressIntoOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', ' receiving_address')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('receiving_address')->nullable()->after('signed_at')->comment('订单的收货地址');
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
