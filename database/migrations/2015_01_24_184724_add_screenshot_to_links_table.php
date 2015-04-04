<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddScreenshotToLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('links', function(Blueprint $table)
		{
			$table->string('screenshot')->nullable()->default(null)->after('page_title');
			$table->string('thumbnail')->nullable()->default(null)->after('screenshot');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('links', function(Blueprint $table)
		{
			$table->dropColumn('screenshot');
			$table->dropColumn('thumbnail');
		});
	}

}
