<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

if ( Auth::check() ) {
	View::share('authUser', Auth::user());
}

// Queues
Route::post('queue/receive', function()
{
	return Queue::marshal();
});

Route::group([ 'namespace' => 'Dyryme\Controllers', ], function()
{
	Route::get('/', [ 'as' => 'create', 'uses' => 'LinkController@create', ]);
	Route::post('store', [ 'as' => 'store', 'before' => 'csrf', 'uses' => 'LinkController@store', ]);
	Route::get('screenshot/{id}', [ 'as' => 'screenshot', 'uses' => 'LinkController@screenshot', ]);

	Route::get('looper', [ 'as' => 'loop_detected', 'uses' => 'LinkController@looper', ]);

	// Authenticated link routes
	Route::group([ 'prefix' => 'link', 'before' => 'auth', ], function ()
	{
		Route::get('list', [ 'as' => 'list', 'uses' => 'LinkController@index', ]);
		Route::delete('{id}', [ 'as' => 'link.destroy', 'before' => 'auth|csrf', 'uses' => 'LinkController@destroy', ]);
		Route::put('{id}', [ 'as' => 'link.activate', 'before' => 'auth|csrf', 'uses' => 'LinkController@activate', ]);
		Route::get('{id}/hits', [ 'as' => 'link.hits', 'uses' => 'LinkController@hits', ]);
	});

	// Authentication routes
	Route::get('login', [ 'as' => 'login', 'uses' => 'AuthController@login', ]);
	Route::post('login', [ 'as' => 'authenticate', 'before' => 'csrf', 'uses' => 'AuthController@authenticate', ]);
	Route::get('logout', [ 'as' => 'logout', 'uses' => 'AuthController@logout', ]);

	// Registration routes
	Route::get('register', [ 'as' => 'register', 'uses' => 'RegistrationController@create', ]);
	Route::post('register', [ 'as' => 'register', 'before' => 'csrf', 'uses' => 'RegistrationController@store', ]);

	// User routes
	Route::get('/user/links', [ 'as' => 'user.links', 'uses' => 'UserController@links', ]);
	Route::get('/denied', [ 'as' => 'user.denied', 'uses' => 'UserController@denied', ]);

	// Wildcard redirect routes
	Route::get('{hash}', [ 'as' => 'redirect', 'uses' => 'LinkController@redirect', ]);
});
