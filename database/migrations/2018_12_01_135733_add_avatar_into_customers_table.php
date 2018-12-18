<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvatarIntoCustomersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('customers', [ 'gender', 'avatar', 'birthed_at', 'store_info' ])) {
            Schema::table('customers', function (Blueprint $table) {
                $table->boolean('gender')->after('remember_token')->nullable()->comment('性别');
                $table->string('avatar')->after('remember_token')->nullable()->comment('头像');
                $table->longText('store_info')->nullable()->after('remember_token')->comment('店铺信息');
                $table->timestamp('birthed_at')->nullable()->after('invitation_code')->comment('出生日期');
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
        if (Schema::hasColumns('customers', [ 'gender', 'avatar', 'birthed_at', 'store_info' ])) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('gender');
                $table->dropColumn('avatar');
                $table->dropColumn('birthed_at');
                $table->dropColumn('store_info');
            });
        }
    }
}
