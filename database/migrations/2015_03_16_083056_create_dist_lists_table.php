<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistListsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dist_lists');
        Schema::create('dist_lists', function(Blueprint $table)
        {
            $table->increments('id')->comment('Unique identifier for distribution list');
            $table->string('name')->comment('Name of the distribution list');
            $table->integer('createdBy')->unsigned()->comment('User id who created the list');
            $table->timestamps();

            $table->index('createdBy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dist_lists');
    }

}
