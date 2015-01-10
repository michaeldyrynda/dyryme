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

Route::when('*', 'csrf', [ 'patch', 'post', 'put', ]);

Route::get('/', [ 'as' => 'create', 'uses' => 'Dyryme\Controllers\LinkController@create', ]);
Route::post('store', [ 'as' => 'store', 'uses' => 'Dyryme\Controllers\LinkController@store', ]);
Route::get('list', [ 'as' => 'list', 'before' => 'auth', 'uses' => 'Dyryme\Controllers\LinkController@index', ]);
Route::delete('link/{id}', [ 'as' => 'link.destroy', 'before' => 'auth', 'uses' => 'Dyryme\Controllers\LinkController@destroy', ]);
Route::put('link/{id}', [ 'as' => 'link.activate', 'before' => 'auth', 'uses' => 'Dyryme\Controllers\LinkController@activate', ]);
Route::get('login', [ 'as' => 'login', 'uses' => 'Dyryme\Controllers\AuthController@login', ]);
Route::post('login', [ 'as' => 'authenticate', 'uses' => 'Dyryme\Controllers\AuthController@authenticate', ]);
Route::get('logout', [ 'as' => 'logout', 'uses' => 'Dyryme\Controllers\AuthController@logout', ]);
Route::get('{hash}', [ 'as' => 'redirect', 'uses' => 'Dyryme\Controllers\LinkController@redirect', ]);
