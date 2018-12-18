<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdCardToSupplierOrgsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('supplier_orgs', [ 'mobile', 'id_card' ])) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->string('id_card')->nullable()->after('is_tip')->comment('证件号码');
                $table->string('mobile')->nullable()->after('is_tip')->comment('手机号');
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
        if (Schema::hasColumns('supplier_orgs', [ 'mobile', 'id_card' ])) {
            Schema::table('supplier_orgs', function (Blueprint $table) {
                $table->dropColumn('mobile');
                $table->dropColumn('id_card');
            });
        }
    }
}
