<?php namespace Dyryme\Providers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @param Guard $auth
	 */
	public function boot(Guard $auth)
	{
		view()->composer('*', function ($view) use ($auth)
		{
			$view->with('authUser', $auth->user());
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
