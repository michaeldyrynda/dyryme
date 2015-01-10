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
	 * @return \Illuminate\View\View
	 */
	public function login()
	{
		return \View::make('login');
	}


	/**
	 * @return $this
	 */
	public function authenticate()
	{
		$input = \Input::only([ 'username', 'password', ]);

		if ( \Auth::attempt($input) )
		{
			return \Redirect::intended('list');
		}

		return \Redirect::back()->withInput(\Input::only([ 'username', ]))->with([
			'login_error' => 'Please check your details and try again',
		]);
	}


}
