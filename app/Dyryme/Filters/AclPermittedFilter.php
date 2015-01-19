<?php namespace Dyryme\Filters;

use Dyryme\Repositories\UserRepository;

/**
 * ACL filter
 *
 * @package    Dyryme
 * @subpackage Filters
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class AclPermittedFilter {

	/**
	 * @var UserRepository
	 */
	private $userRepository;


	/**
	 * @param UserRepository $userRepository
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}


	/**
	 * @param $route
	 *
	 * @return bool|\Illuminate\Http\RedirectResponse
	 */
	public function filter($route)
	{
		// Superuser has access to all of the things
		if ( \Auth::user()->superuser ) {
			return true;
		}

		$user = $this->userRepository->getPermissions(\Auth::user()->id);

		foreach ($user->groups as $group)
		{
			if ( $group->permissions->has($route->getName()) )
			{
				return true;
			}
		}

		return \Redirect::route('user.denied');
	}


}
