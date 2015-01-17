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

		$start = (new \DateTime())->sub(new \DateInterval('P7D'));
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

		$hits = $link->hits()->orderBy('created_at', 'desc')->paginate(40);

		return \View::make('hits')->with(compact('link', 'hits'));
	}


	/**
	 * Get the daily links data table
	 *
	 * @param \DateTime $start
	 * @param \DateTime $end
	 *
	 * @return \Lava::DataTable
	 */
	private function getDailyLinksTable(\DateTime $start, \DateTime $end)
	{
		$dailyLinkBreakdown = $this->linkRepository->getDailyBreakdown($start, $end);

		$dailyLinksTable = \Lava::DataTable();
		$dailyLinksTable->addDateColumn('Date')->addNumberColumn('New Links')->setTimezone('Australia/Adelaide');

		foreach ($dailyLinkBreakdown as $day)
		{
			$dailyLinksTable->addRow([ $day->date, $day->links, ]);
		}

		return $dailyLinksTable;
	}


	/**
	 * Get the daily hits data table
	 *
	 * @param \DateTime $start
	 * @param \DateTime $end
	 *
	 * @return \Lava::DataTable
	 */
	private function getDailyHitsTable(\DateTime $start, \DateTime $end)
	{
		$dailyHitBreakdown = $this->hitLogRepository->getDailyBreakdown($start, $end);

		$dailyHitsTable = \Lava::DataTable();
		$dailyHitsTable->addDateColumn('Date')->addNumberColumn('New Hits')->setTimezone('Australia/Adelaide');

		foreach ($dailyHitBreakdown as $day)
		{
			$dailyHitsTable->addRow([ $day->date, $day->hits, ]);
		}

		return $dailyHitsTable;
	}


}
