<?php

class AclUserGroupsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('acl_user_groups')->truncate();

		User::find(1)->groups()->sync([ 1, 2, ]); // Add first user to admin, users groups
	}

}
