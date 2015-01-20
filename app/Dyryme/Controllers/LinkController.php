<?php namespace Dyryme\Controllers;

use Dyryme\Repositories\HitLogRepository;
use Dyryme\Repositories\LinkRepository;
use Dyryme\Exceptions\ValidationFailedException;
use Dyryme\Utilities\RemoteClient;

/**
 * Link controller
 *
 * @package	Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author	 Michael Dyrynda <michael@iatstuti.net>
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
	 * @param RemoteClient	 $remoteClient
	 */
	function __construct(LinkRepository $linkRepository, HitLogRepository $hitLogRepository, RemoteClient $remoteClient)
	{
		parent::__construct();

		$this->linkRepository   = $linkRepository;
		$this->hitLogRepository = $hitLogRepository;
		$this->remoteClient     = $remoteClient;

		$this->beforeFilter('auth');
		$this->beforeFilter('acl.permitted', [ 'only' => [ 'index', 'destroy', ], ]);
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

		$start = (new \DateTime())->sub(new \DateInterval('P6D'));
		$end   = new \DateTime();

		$dailyLinksTable = $this->getDailyLinksTable($start, $end);
		$dailyHitsTable  = $this->getDailyHitsTable($start, $end);

		\Lava::ColumnChart('DailyLinksChart')->setOptions([
			'datatable' => $dailyLinksTable,
		]);

		\Lava::ColumnChart('DailyHitsChart')->setOptions([
			'datatable' => $dailyHitsTable,
		]);

		return \View::make('list')->with(compact('links', 'popular', 'creators'));
	}


	/**
	 * Store a url in the database
	 */
	public function store()
	{
		// Try and weed out some of the bots spamming links
		if ( is_null($this->remoteClient->getUserAgent()) )
		{
			\Log::info('Ignored link request from remote client with no user agent', [
				'ipAddress' => $this->remoteClient->getIpAddress(),
				'hostname'  => $this->remoteClient->getHostname(),
				'userAgent' => $this->remoteClient->getUserAgent(),
			]);

			\App::abort(403, 'Unauthorised Action');
		}

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
			$flash_message = 'Could not delete link with id ' . e($id);
		}

		$flash_message = 'Successfully deleted link with id ' . e($id);

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
			$flash_message = 'Could not restore link with id ' . e($id);
		}

		$flash_message = 'Successfully restored link with id ' . e($id);

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

		$hits = $link->hits()->orderBy('created_at', 'desc')->paginate(40);

		if ( ! \Auth::check() || ( ! \Auth::user()->isSuperUser() && \Auth::id() !== $link->user_id ) )
		{
			return \Redirect::route('user.denied');
		}

		return \View::make('hits')->with(compact('link', 'hits'));
	}


	/**
	 * Get the daily links data table
	 *
	 * @param \DateTime $start
	 *
	 * @return \Lava::DataTable
	 */
	private function getDailyLinksTable(\DateTime $start)
	{
		return $this->getLinksTable($start, 'links', $this->linkRepository);
	}


	/**
	 * Get the daily hits data table
	 *
	 * @param \DateTime $start
	 *
	 * @return \Lava::DataTable
	 */
	private function getDailyHitsTable(\DateTime $start)
	{
		return $this->getLinksTable($start, 'hits', $this->hitLogRepository);
	}


	/**
	 * @param \DateTime $start
	 * @param           $column
	 * @param           $repository
	 *
	 * @return mixed
	 */
	private function getLinksTable(\DateTime $start, $column, $repository)
	{
		$breakdown = $repository->getDailyBreakdown($start);

		$table = \Lava::DataTable();
		$table->addDateColumn('Date')->addNumberColumn(\Str::title($column))->setTimezone('Australia/Adelaide');

		foreach ($breakdown as $day)
		{
			$table->addRow([ $day->date, $day->{$column}, ]);
		}

		return $table;
	}


}
