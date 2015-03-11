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
			$table->string('phoneNumber', 20);
			$table->string('email')->unique();
			$table->string('password', 60);
            $table->string('userId')->default("0");
            $table->enum('userType', array('facebook', 'google', 'normal'))->default('normal');
			$table->rememberToken();
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
        Schema::dropIfExists('users');
	}

}
