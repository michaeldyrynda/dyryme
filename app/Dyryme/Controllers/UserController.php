<?php namespace Dyryme\Controllers;

use Dyryme\Repositories\UserRepository;

/**
 * User controller
 *
 * @package    Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class UserController extends \BaseController {

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
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function links()
	{
		if ( \Auth::guest() )
		{
			return \Redirect::route('login');
		}

		$user = $this->userRepository->userWithLinks(\Auth::user()->id);

		return \View::make('user.links')->withUser($user);
	}


	/**
	 * @return \Illuminate\View\View
	 */
	public function denied()
	{
		return \View::make('user.denied');
	}

}
