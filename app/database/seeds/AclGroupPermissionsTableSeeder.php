<?php

use Dyryme\Models\AclGroup;

class AclGroupPermissionsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('acl_group_permissions')->truncate();

		AclGroup::find(1)->permissions()->sync([ 1, 2, ]); // Add all permissions to admin group
		AclGroup::find(2)->permissions()->sync([ 2, ]);    // Add user permissions to user group
	}

}
