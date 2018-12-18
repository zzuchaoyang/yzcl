<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoIntoDriversTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('drivers', [ 'gender', 'avatar', 'birthed_at' ])) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->boolean('gender')->after('status')->nullable()->comment('性别');
                $table->string('avatar')->after('status')->nullable()->comment('头像');
                $table->timestamp('birthed_at')->nullable()->after('hiredate_on')->comment('出生日期');
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
        if (Schema::hasColumns('drivers', [ 'gender', 'avatar', 'birthed_at' ])) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->dropColumn('gender');
                $table->dropColumn('avatar');
                $table->dropColumn('birthed_at');
            });
        }
    }
}
