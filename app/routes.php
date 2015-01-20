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

Route::when('*', 'csrf', [ 'patch', 'post', 'put', ]);

Route::get('/', [ 'as' => 'create', 'uses' => 'Dyryme\Controllers\LinkController@create', ]);
Route::post('store', [ 'as' => 'store', 'uses' => 'Dyryme\Controllers\LinkController@store', ]);

// Authenticated link routes
Route::group([ 'prefix' => 'link', 'before' => 'auth', ], function ()
{
	Route::get('list', [ 'as' => 'list', 'before' => [ 'auth', ], 'uses' => 'Dyryme\Controllers\LinkController@index', ]);
	Route::delete('{id}', [ 'as' => 'link.destroy', 'before' => 'auth', 'uses' => 'Dyryme\Controllers\LinkController@destroy', ]);
	Route::put('{id}', [ 'as' => 'link.activate', 'before' => 'auth', 'uses' => 'Dyryme\Controllers\LinkController@activate', ]);
	Route::get('{id}/hits', [ 'as' => 'link.hits', 'before' => 'auth', 'uses' => 'Dyryme\Controllers\LinkController@hits', ]);
});

// Authentication routes
Route::get('login', [ 'as' => 'login', 'uses' => 'Dyryme\Controllers\AuthController@login', ]);
Route::post('login', [ 'as' => 'authenticate', 'uses' => 'Dyryme\Controllers\AuthController@authenticate', ]);
Route::get('logout', [ 'as' => 'logout', 'uses' => 'Dyryme\Controllers\AuthController@logout', ]);

// Registration routes
Route::get('register', [ 'as' => 'register', 'uses' => 'Dyryme\Controllers\RegistrationController@create', ]);
Route::post('register', [ 'as' => 'register', 'uses' => 'Dyryme\Controllers\RegistrationController@store', ]);

// User routes
Route::get('links', [ 'as' => 'user.links', 'uses' => 'Dyryme\Controllers\UserController@links', ]);
Route::get('denied', [ 'as' => 'user.denied', 'uses' => 'Dyryme\Controllers\UserController@denied', ]);

// Wildcard redirect routes
Route::get('{hash}', [ 'as' => 'redirect', 'uses' => 'Dyryme\Controllers\LinkController@redirect', ]);
