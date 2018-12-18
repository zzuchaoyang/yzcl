<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mobile')->unique()->index();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $supplier = new \App\Models\Supplier\Supplier();
        $supplier->fill([
            'name' => 'admin',
            'mobile' => 'admin',
        ]);

        $supplier->password = bcrypt('123456');

        $supplier->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
