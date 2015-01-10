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

Route::get('/', [ 'as' => 'create', 'uses' => 'Dyryme\Controllers\LinkController@create', ]);
Route::post('store', [ 'as' => 'store', 'uses' => 'Dyryme\Controllers\LinkController@store', ]);
Route::get('{hash}', [ 'as' => 'redirect', 'uses' => 'Dyryme\Controllers\LinkController@redirect', ]);
