<?php namespace Dyryme\Http\Controllers;

use Dyryme\Repositories\LinkRepository;
use Dyryme\Repositories\UserRepository;

/**
 * User controller
 *
 * @package    Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class UserController extends Controller {

	/**
	 * @var UserRepository
	 */
	protected $userRepository;

	/**
	 * @var LinkRepository
	 */
	private $linkRepository;


	/**
	 * @param UserRepository $userRepository
	 */
	function __construct(UserRepository $userRepository, LinkRepository $linkRepository)
	{
		$this->userRepository = $userRepository;
		$this->linkRepository = $linkRepository;

		$this->beforeFilter('auth');
		$this->beforeFilter('acl.permitted', [ 'only' => 'links', ]);
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

		$links = $this->linkRepository->getAllForList(\Auth::id());

		return \View::make('user.links')->withLinks($links);
	}


	/**
	 * @return \Illuminate\View\View
	 */
	public function denied()
	{
		return \View::make('user.denied');
	}

}
