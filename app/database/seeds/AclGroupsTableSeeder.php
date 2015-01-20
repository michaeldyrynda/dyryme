<?php

use Dyryme\Models\AclGroup;

class AclGroupsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('acl_groups')->truncate();

		AclGroup::create([
			'id'          => 1,
			'name'        => 'Admin',
			'description' => 'Admin users group',
		]);

		AclGroup::create([
			'id'          => 2,
			'name'        => 'Users',
			'description' => 'General users group',
		]);
	}

}
