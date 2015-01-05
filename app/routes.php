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

Route::get('/', function()
{
	return View::make('home');
});


Route::post('store', [ 'as' => 'store', 'uses' => 'Dyryme\Controllers\LinkController@store', ]);
Route::get('{hash}', [ 'as' => 'redirect', 'uses' => 'Dyryme\Controllers\LinkController@rediret', ]);
