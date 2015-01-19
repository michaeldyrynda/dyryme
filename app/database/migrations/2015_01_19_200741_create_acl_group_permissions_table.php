<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAclGroupPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('acl_group_permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('group_id')->unsigned();
			$table->integer('permission_id')->unsigned();
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
		Schema::drop('acl_group_permissions');
	}

}
