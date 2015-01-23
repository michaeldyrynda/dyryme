<?php namespace Dyryme\Controllers;

/**
 * Authentication controller
 *
 * @package    Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class AuthController extends \BaseController {

	/**
	 * Display login form
	 *
	 * @return \Illuminate\View\View
	 */
	public function login()
	{
		return \View::make('login');
	}


	/**
	 * Logout method
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function logout()
	{
		\Auth::logout();

		return \Redirect::route('create');
	}


	/**
	 * Authenticate a user
	 *
	 * @return $this
	 */
	public function authenticate()
	{
		$input = \Input::only([ 'username', 'password', ]);

		if ( \Auth::attempt($input, true) )
		{
			return \Redirect::intended('/');
		}

		return \Redirect::back()->withInput(\Input::only([ 'username', ]))->with([
			'login_error' => 'Please check your details and try again',
		]);
	}


}
