<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DistListMembers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dist_list_members');

        Schema::create('dist_list_members', function(Blueprint $table)
        {
            $table->integer('dist_list_id')->unsigned()->comment('The distribution list id');
//            $table->foreign('dist_list_id')->references('id')->on('dist_lists')->onDelete('cascade');

            $table->integer('user_id')->unsigned()->comment('The user id who is member of the group');
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();

            $table->index('dist_list_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dist_list_members');
    }

}
