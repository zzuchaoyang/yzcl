<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 供应商-司机
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('员工姓名');;
            $table->string('mobile')->unique()->index();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedInteger('supplier_org_id')->index();
            $table->boolean('status')->comment('状态：0 停用 1 正常');
            $table->string('id_card')->nullable()->comment('证件号码');
            $table->string('license')->nullable()->comment('常用车牌');
            $table->timestamp('hiredate_on')->nullable()->comment('入职日期');

            $table->timestamps();
        });

        // 供应商-组织架构
        Schema::create('supplier_orgs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名称');
            $table->string('type')->comment('类型：部门 or 人员');
            $table->boolean('status')->comment('状态：0 禁用 1 启用');

            NestedSet::columns($table);

            $table->boolean('is_tip')->comment('是否为末端：0 否 1 是');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_orgs');
        Schema::dropIfExists('drivers');
    }
}
