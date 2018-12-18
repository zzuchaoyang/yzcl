<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierIdIntoSupplierOrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('supplier_orgs', 'supplier_id')) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->unsignedInteger('supplier_id')->after('id');

                $table->index(['supplier_id']);
            });
        }

        if (!Schema::hasColumn('supplier_orgs', 'category')) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->string('category', 25)->nullable()->after('name')->comment('分类');
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
        if (Schema::hasColumn('supplier_orgs', 'supplier_id')) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->dropColumn('supplier_id');
            });
        }

        if (Schema::hasColumn('supplier_orgs', 'category')) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
}
