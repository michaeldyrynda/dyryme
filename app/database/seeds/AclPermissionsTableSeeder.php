<?php

use Dyryme\Models\AclPermission;

class AclPermissionsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('acl_permissions')->truncate();

		AclPermission::create([
			'id'          => 1,
			'ident'       => 'link.list',
			'description' => 'Provide access to the global links list',
		]);

		AclPermission::create([
			'id'          => 2,
			'ident'       => 'user.links',
			'description' => 'Provide access to an individual user links',
		]);
	}

}
