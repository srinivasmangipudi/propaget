<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('properties');
        Schema::create('properties', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('agentId');
            $table->integer('clientId');
            $table->string('type', '100');
            $table->string('location', '100');
            $table->string('area', '255');
            $table->string('price', '100');
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
