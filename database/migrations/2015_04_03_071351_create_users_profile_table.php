<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProfileTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_profile');
        Schema::create('users_profile', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('The user id who is member of the group');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('uid')->default("0");
            $table->enum('user_type', array('facebook', 'google', 'normal'))->default('normal')->comment('How the user was created. Facebook, Google etc');
            $table->longText('data');
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
        Schema::dropIfExists('users_profile');
    }

}
