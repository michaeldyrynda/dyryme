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
		$this->app['validator']->extend('no_recursion', function ($attribute, $value, $parameters)
		{
			return ! starts_with($value, [ 'http://dyry.me', 'https://dyry.me', ]);
		});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
