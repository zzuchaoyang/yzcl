<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCooperationApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('cooperation_applications')) {
            Schema::create('cooperation_applications', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('sender_id')->index()->comment('申请合作人id');
                $table->string('sender_type')->comment('申请合作人类型');
                $table->unsignedInteger('receiver_id')->index()->comment('接受合作人id');
                $table->string('receiver_type')->comment('接受合作人类型');
                $table->timestamp('rejected_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cooperation_application');
    }
}
