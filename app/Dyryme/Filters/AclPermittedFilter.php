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
		$permitted = false;

		// Superuser has access to all of the things
		if ( ! \Auth::user()->superuser )
		{
			$user = $this->userRepository->getUserPermissions(\Auth::user()->id);

			foreach ($user->groups as $group)
			{
				if ( $group->permissions->has($route->getName()) && ! $permitted )
				{
					$permitted = true;
				}
			}

			if ( ! $permitted )
			{
				return \Redirect::route('user.denied');
			}
		}
	}


}
