<?php namespace Dyryme\Providers;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		Validator::extend('no_recursion', function ($attribute, $value, $parameters)
		{
			return ! starts_with($value, [ 'http://dyry.me', 'https://dyry.me', ]);
		});
	}

}
