<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		$this->call('UsersTableSeeder');
		$this->call('AclGroupsTableSeeder');
		$this->call('AclPermissionsTableSeeder');
		$this->call('AclGroupPermissionsTableSeeder');
		$this->call('AclUserGroupsTableSeeder');

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
	}

}
