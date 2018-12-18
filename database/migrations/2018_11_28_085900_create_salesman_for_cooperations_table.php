<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesmanForCooperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('salesman_for_cooperations')) {
            Schema::create('salesman_for_cooperations', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('cooperation_application_id')->comment('合作关系id');
                $table->unsignedInteger('salesman_id')->comment('为合作关系服务的业务员id');
                $table->timestamps();
            });
        }
        if (!Schema::hasColumn('cooperation_applications', 'accepted_at')) {
            Schema::table('cooperation_applications', function (Blueprint $table) {
                $table->timestamp('accepted_at')->nullable()->comment('合作关系建立时间');
            });
        }
        if (!Schema::hasColumn('customer_supplier', 'cooperation_application_id')) {
            Schema::table('customer_supplier', function (Blueprint $table) {
                $table->unsignedInteger('cooperation_application_id')->nullable()->comment('合作关系id');
            });
        }
        if (Schema::hasColumn('customer_supplier', 'salesmen_ids')) {
            Schema::table('customer_supplier', function (Blueprint $table) {
                $table->dropColumn('salesmen_ids');
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
        Schema::dropIfExists('salesman_for_cooperation');
    }
}
