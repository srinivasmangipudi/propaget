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
        Schema::dropIfExists('registered_devices');

        Schema::create('registered_devices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('device_id')->comment('This will store the IMEI number of the phone');
            $table->string('registraion_id')->comment('This is the reg id send from Google for GCM notification');
            $table->integer('user_id')->unsigned()->comment('The user to which this device is registered with');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registered_devices');
    }

}
