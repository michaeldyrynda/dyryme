<?php namespace Dyryme\Controllers;

use Dyryme\Exceptions\ValidationFailedException;

/**
 * Registration controller
 *
 * @package    Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class RegistrationController extends \BaseController {

	/**
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return \View::make('registration.create');
	}


	/**
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		$input = \Input::only('email_address', 'password', 'password_confirmation');

		try
		{
			\Event::fire('user.creating', [ $input, ]);

			$user = $this->userRepository->store([
				'username' => $input['email_address'],
				'password' => Hash::make($input['password']),
			]);

			if ( $user && \Auth::login($user) )
			{
				return \Redirect::route('user.links');
			}
		}
		catch (ValidationFailedException $e)
		{
			return \Redirect::back()->withErrors($e->getErrors())->onlyInput('username');
		}
	}


}
