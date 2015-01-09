<?php namespace Dyryme\Controllers;

use Dyryme\Repositories\LinkRepository;
use Dyryme\Utilities\RemoteClient;
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
	 * @var RemoteClient
	 */
	private $remoteClient;


	/**
	 * @param LinkRepository $repository
	 */
	function __construct(LinkRepository $repository, RemoteClient $remoteClient)
	{
		parent::__construct();

		$this->repository   = $repository;
		$this->remoteClient = $remoteClient;
	}


	/**
	 * @return mixed
	 */
	public function index()
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

				$remoteAddress = $this->remoteClient->getIpAddress();
				$hostname      = $this->remoteClient->getHostname();
				$userAgent     = $this->remoteClient->getUserAgent();

				$hash = $this->repository->store(compact('url', 'hash', 'remoteAddress', 'hostname', 'userAgent'))->hash;
			}
			catch (ValidationFailedException $e)
			{
				return \Redirect::home()->withErrors($e->getErrors())->withInput();
			}
		}

		return \Redirect::home()->with([
			'flash_message' => sprintf('Your URL has successfully been shortened to %s', link_to($hash)),
			'hash'          => $hash,
		]);
	}


}
