<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLinksUserId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('links', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->nullable()->default(null)->after('id');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
			$table->dropForeign('links_user_id_foreign');
			$table->dropColumn('user_id');
		});
	}

}
