<?php namespace Dyryme\Controllers;

use Carbon\Carbon;
use Dyryme\Exceptions\LooperException;
use Dyryme\Exceptions\PermissionDeniedException;
use Dyryme\Handlers\LinkHandler;
use Dyryme\Models\Link;
use Dyryme\Repositories\HitLogRepository;
use Dyryme\Repositories\LinkRepository;
use Dyryme\Utilities\RemoteClient;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
	 * @var LinkHandler
	 */
	private $linkHandler;


	/**
	 * @param LinkRepository   $linkRepository
	 * @param HitLogRepository $hitLogRepository
	 * @param RemoteClient     $remoteClient
	 * @param LinkHandler      $linkHandler
	 */
	function __construct(LinkRepository $linkRepository, HitLogRepository $hitLogRepository, RemoteClient $remoteClient, LinkHandler $linkHandler)
	{
		$this->linkRepository   = $linkRepository;
		$this->hitLogRepository = $hitLogRepository;
		$this->remoteClient     = $remoteClient;
		$this->linkHandler      = $linkHandler;

		$this->beforeFilter('auth', [ 'only' => [ 'index', 'destroy', ], ]);
		$this->beforeFilter('acl.permitted', [ 'only' => [ 'index', ], ]);
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

		$start = Carbon::createFromTime(0)->subDays(6);
		$end   = Carbon::now();

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
		$input = \Input::only('longUrl');
		$hash  = $this->linkHandler->make($input);

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

		$this->protectAgainstLooping($link);

		if ( $this->remoteClient->isHitler() )
		{
			return \Redirect::to('http://jewoven.com');
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
		$link = $this->linkRepository->lookupById($id);

		$this->authPermissionCheck($link->user_id);

		if ( ! $link->delete() )
		{
			$flash_message = 'Could not delete link with id ' . e($id);
		}

		$flash_message = 'Successfully deleted link with id ' . e($id);

		return \Redirect::back()->with(compact('flash_message'));
	}


	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function activate($id)
	{
		$link = $this->linkRepository->lookupById($id);

		$this->authPermissionCheck($link->user_id);

		if ( ! $link->restore() )
		{
			$flash_message = 'Could not restore link with id ' . e($id);
		}

		$flash_message = 'Successfully restored link with id ' . e($id);

		return \Redirect::back()->with(compact('flash_message'));
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
			return \Redirect::back()->with([ 'flash_message' => 'Could not find specified link', ]);
		}

		$this->authPermissionCheck($link->user_id);

		$hits = $link->hits()->orderBy('created_at', 'desc')->paginate(40);

		return \View::make('hits')->with(compact('link', 'hits'));
	}


	/**
	 * Get the daily links data table
	 *
	 * @param Carbon $start
	 *
	 * @return \Lava::DataTable
	 */
	private function getDailyLinksTable(Carbon $start)
	{
		return $this->getLinksTable($start, 'links', $this->linkRepository);
	}


	/**
	 * Get the daily hits data table
	 *
	 * @param Carbon $start
	 *
	 * @return \Lava::DataTable
	 */
	private function getDailyHitsTable(Carbon $start)
	{
		return $this->getLinksTable($start, 'hits', $this->hitLogRepository);
	}


	/**
	 * @param Carbon $start
	 * @param        $column
	 * @param        $repository
	 *
	 * @return mixed
	 */
	private function getLinksTable(Carbon $start, $column, $repository)
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


	/**
	 * @return \Illuminate\View\View
	 */
	public function looper()
	{
		return \View::make('loop_detected');
	}


	/**
	 * Serve the screenshot for a given link identifier
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function screenshot($id)
	{
		$thumbnail = \Request::has('thumb');

		try
		{
			$link = $this->linkRepository->lookupById($id);

			if ( ( $thumbnail && ! is_null($link->thumbnail) || ( ! $thumbnail && ! is_null($link->screenshot) ) ) )
			{
				return \Image::make($thumbnail ? $link->thumbnail : $link->screenshot)->response();
			}
		}
		catch (ModelNotFoundException $e)
		{
			//	no-op
		}

		return \Image::make(storage_path() . '/screenshots/no_image.jpg')->response();
	}


	/**
	 * @param $userId
	 *
	 * @throws PermissionDeniedException
	 */
	private function authPermissionCheck($userId)
	{
		if ( ! \Auth::check() || ( ! \Auth::user()->isSuperUser() && \Auth::id() !== $userId ) )
		{
			throw new PermissionDeniedException;
		}
	}


	/**
	 * @param Link $link
	 *
	 * @throws LooperException
	 */
	private function protectAgainstLooping(Link $link)
	{
		$hits = $this->hitLogRepository->countByAddress($link->id);

		if ( $hits > 3 )
		{
			\Event::fire('link.forceDeleting', [ $link, ]);

			// Make it gone for good so that the user can't just re-enable it
			$link->forceDelete();

			throw new LooperException;
		}
	}


}
