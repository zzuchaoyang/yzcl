<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvitationCodeIntoCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customers', 'invitation_code')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('invitation_code')->nullable()->after('remember_token')->comment('邀请码');
            });
        }

        if (Schema::hasColumn('customers', 'name')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('name')->nullable()->change();
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
        if (Schema::hasColumn('customers', 'invitation_code')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('invitation_code');
            });
        }
    }
}
