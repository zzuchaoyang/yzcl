<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRankIntoSupplierOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('supplier_orgs', 'rank')) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->string('rank', 25)->nullable()->after('parent_id')->comment('级别');
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
        if (Schema::hasColumn('supplier_orgs', 'rank')) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->dropColumn('rank');
            });
        }
    }
}
