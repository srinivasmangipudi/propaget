<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequirementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('requirements');
        Schema::create('requirements', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('agent_id');
            $table->integer('client_id');
            $table->string('client_email', '255');
            $table->string('title', '255');
            $table->text('description');
            $table->string('type', '100');
            $table->string('location', '100');
            $table->string('area', '255');
            $table->string('range', '255');
            $table->string('price', '100');
            $table->string('price_range', '100');
            $table->integer('approved');
            $table->timestamps();
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requirements');
    }

}
