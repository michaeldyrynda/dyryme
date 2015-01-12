<?php namespace Dyryme\Controllers;

use Dyryme\Repositories\HitLogRepository;
use Dyryme\Repositories\LinkRepository;
use Dyryme\Exceptions\ValidationFailedException;

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
	 * @return mixed
	 */
	public function index()
	{
		$links    = $this->linkRepository->getAllForList();
		$popular  = $this->linkRepository->getTopLinks();
		$creators = $this->linkRepository->getTopCreators();

		return \View::make('list')->with(compact('links', 'popular', 'creators'));
	}


	/**
	 * Store a url in the database
	 */
	public function store()
	{
        $input = \Input::only('longUrl', 'description');

		list( $hash, $existing ) = $this->linkRepository->makeHash($input['longUrl']);

		if ( ! $existing )
		{
			try
			{
				\Event::fire('link.creating', [ [ 'url' => $input['longUrl'], 'hash' => $hash, ] ]);

                $hash = $this->linkRepository->store([
                    'url'         => $input['longUrl'],
                    'description' => $input['description'],
                    'hash'        => $hash,
                ])->hash;
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

		return \Redirect::to($link->url);
	}


	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id)
	{
		if ( ! $this->linkRepository->lookupById($id)->delete() )
		{
			$flash_message = 'Could not delete link with id ' . htmlspecialchars($id);
		}

		$flash_message = 'Successfully deleted link with id ' . htmlspecialchars($id);

		return \Redirect::to('list')->with(compact('flash_message'));
	}


	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function activate($id)
	{
		if ( ! $this->linkRepository->lookupById($id)->restore() )
		{
			$flash_message = 'Could not restore link with id ' . htmlspecialchars($id);
		}

		$flash_message = 'Successfully restored link with id ' . htmlspecialchars($id);

		return \Redirect::to('list')->with(compact('flash_message'));
	}


	/**
	 * @param $linkId
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function hits($linkId)
	{
		if ( ! ( $link = $this->linkRepository->lookupById($linkId) ) )
		{
			return \Redirect::route('list')->with([ 'flash_message' => 'Could not find specified link', ]);
		}

		$hits = $link->hits()->paginate(40);

		return \View::make('hits')->with(compact('link', 'hits'));
	}


}
