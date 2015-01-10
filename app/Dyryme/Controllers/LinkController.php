<?php namespace Dyryme\Controllers;

use Dyryme\Repositories\HitLogRepository;
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
	private $linkRepository;

	/**
	 * @var HitLogRepository
	 */
	private $hitLogRepository;


	/**
	 * @param LinkRepository   $linkRepository
	 * @param HitLogRepository $hitLogRepository
	 */
	function __construct(LinkRepository $linkRepository, HitLogRepository $hitLogRepository)
	{
		parent::__construct();

		$this->linkRepository   = $linkRepository;
		$this->hitLogRepository = $hitLogRepository;
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
		$url = \Input::get('url');
		list( $hash, $existing ) = $this->linkRepository->makeHash($url);

		if ( ! $existing )
		{
			try
			{
				\Event::fire('link.creating', [ compact('url', 'hash') ]);

				$hash = $this->linkRepository->store(compact('url', 'hash'))->hash;
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
		$link = $this->linkRepository->lookupByHash($hash);

		if ( ! $link )
		{
			return \Redirect::route('create')->with([
				'flash_message' => 'The specified short url could not be found',
			]);
		}

		$this->hitLogRepository->store($link);

		return \Redirect::url($link->url);
	}


}
