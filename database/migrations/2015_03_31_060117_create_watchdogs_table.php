<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatchdogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watchdogs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('User id related to the event');
            $table->string('message')->comment('The general message for the watchdog event');
            $table->enum('type', ['normal', 'error', 'warning'])->comment('The type of event');
            $table->binary('data')->comment('The required data during the event');
            $table->string('hostmane')->comment('The hostname from where the event was fired');
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
        Schema::drop('watchdogs');
    }

}
