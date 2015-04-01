<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('phone_number', 20);
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('user_id')->default("0");
            $table->enum('role', array('agent', 'client','anonymous'))->default('anonymous')->comment('Role of User in System');
            $table->string('experience');
            $table->text('summary');
            $table->enum('user_type', array('facebook', 'google', 'normal'))->default('normal')->comment('How the user was created. Facebook, Google etc');
            $table->rememberToken();
            $table->timestamps();
            $table->enum('status', array(0,1,2))->default(1)->comment('The user status 0: Inactive 1: Active 2: Banned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

}
