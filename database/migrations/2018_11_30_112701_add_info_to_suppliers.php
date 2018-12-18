<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToSuppliers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('suppliers', 'info')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->longText('info')->nullable()->after('invitation_code')->comment('基本信息');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('suppliers', 'info')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('info');
            });
        }
    }
}
