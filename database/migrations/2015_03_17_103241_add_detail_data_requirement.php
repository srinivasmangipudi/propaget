<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailDataRequirement extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('requirements', function(Blueprint $table)
		{
			//
            $table->string('clientEmail', '255')->after('clientid');
            $table->string('title', '255')->after('clientEmail');
            $table->text('description')->after('title');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('requirements', function(Blueprint $table)
		{
			$table->dropColumn('clientEmail');
			$table->dropColumn('title');
			$table->dropColumn('description');
		});
	}

}
