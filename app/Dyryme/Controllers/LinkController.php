<?php namespace Dyryme\Controllers;

use Dyryme\Repositories\LinkRepository;
use Dyryme\Validators\ValidationFailedException;

/**
 * Link controller
 *
 * @package    Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class LinkController extends \BaseController {

	/**
	 * @var LinkRepository
	 */
	private $repository;


	/**
	 * @param LinkRepository $repository
	 */
	function __construct(LinkRepository $repository)
	{
		parent::__construct();

		$this->repository   = $repository;
	}


	/**
	 * @return mixed
	 */
	public function create()
	{
		return \View::make('home');
	}


	/**
	 * Store a url in the database
	 */
	public function store()
	{
		$url  = \Input::get('url');
		list($hash, $existing) = $this->repository->makeHash($url);

		if ( ! $existing )
		{
			try
			{
				\Event::fire('link.creating', [ compact('url', 'hash') ]);

				$hash = $this->repository->store(compact('url', 'hash'))->hash;
			}
			catch (ValidationFailedException $e)
			{
				return \Redirect::route('create')->withErrors($e->getErrors())->withInput();
			}
		}

		return \Redirect::route('create')->with([
			'flash_message' => sprintf('Your URL has successfully been shortened to %s', link_to($hash)),
			'hash'          => $hash,
		]);
	}


	/**
	 * Perform a redirection for the given hash
	 *
	 * @param $hash
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function redirect($hash)
	{
		$link = $this->repository->lookupByHash($hash);

		if ( ! $link )
		{
			return \Redirect::route('create')->with([
				'flash_message' => 'The specified short url could not be found',
			]);
		}

		$this->repository->logHit($hash);

		return \Redirect::url($link->url);
	}


}
