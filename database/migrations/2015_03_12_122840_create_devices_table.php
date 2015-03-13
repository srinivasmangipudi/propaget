<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('devices');
        Schema::create('devices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deviceId')->comment('This will store the IMEI number of the phone');
            $table->string('registraionId')->comment('This is the reg id send from Google for GCM notification');
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
        Schema::dropIfExists('devices');
	}

}
