<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInnerMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('inner_messages')) {
            Schema::create('inner_messages', function (Blueprint $table) {
                $table->increments('id');
                $table->string('type')->nullable()->index()->comment('类型');
                $table->unsignedInteger('sender_id')->index()->comment('发送人id');
                $table->string('sender_type')->nullable()->index()->comment('发送人类型');
                $table->unsignedInteger('receiver_id')->index()->comment('接收人id');
                $table->string('receiver_type')->nullable()->index()->comment('接收人类型');
                $table->unsignedInteger('relation_id')->index()->comment('关联id');
                $table->string('relation_type')->nullable()->comment('关联类型');
                $table->text('content')->nullable()->comment('信息');
                $table->tinyInteger('is_read')->default(0)->index()->comment('是否已读');
                $table->timestamps();
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
        Schema::dropIfExists('inner_messages');
    }
}
