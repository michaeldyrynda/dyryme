<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->truncate();

		User::create([
			'username'  => 'michael@iatstuti.net',
			'password'  => Hash::make('Pass@word1'),
			'superuser' => true,
		]);
	}

}
