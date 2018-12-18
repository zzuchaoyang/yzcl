<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierIdIntoDriversTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('drivers', 'supplier_id')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->unsignedInteger('supplier_id')->after('remember_token');

                $table->index([ 'supplier_id' ]);
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
        if (Schema::hasColumn('drivers', 'supplier_id')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->dropColumn('supplier_id');
            });
        }
    }
}
