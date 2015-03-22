<?php namespace Dyryme\Controllers;

use Dyryme\Exceptions\ValidationFailedException;
use Dyryme\Repositories\UserRepository;

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
	 * @var UserRepository
	 */
	protected $userRepository;


	/**
	 * @param UserRepository $userRepository
	 */
	function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}


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
				'password' => $input['password'],
			]);

			if ( $user && \Auth::attempt([ 'username' => $input['email_address'], 'password' => $input['password'], ], true) )
			{
				return \Redirect::route('create')->withFlashMessage('Successfully registered an account for username ' . e($input['email_address']));
			}

			return \Redirect::back()->onlyInput('email_address');
		}
		catch (ValidationFailedException $e)
		{
			return \Redirect::back()->withErrors($e->getErrors())->onlyInput('email_address');
		}
	}


}
