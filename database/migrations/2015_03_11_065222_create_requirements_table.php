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
            $table->integer('agentId');
            $table->integer('clientId');
            $table->string('type', '100');
            $table->string('location', '100');
            $table->string('area', '255');
            $table->string('range', '255');
            $table->string('price', '100');
            $table->string('priceRange', '100');
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
