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
            $table->increments('id')->comment('Unique identifier');
            $table->integer('distListId')->unsigned()->comment('The distribution list id');
            $table->integer('userId')->unsigned()->comment('The user id who is member of the group');
            $table->timestamps();

            $table->index('distListId');
            $table->index('userId');
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
