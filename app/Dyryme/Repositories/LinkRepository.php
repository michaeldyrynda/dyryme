<?php namespace Dyryme\Repositories;

use Dyryme\Models\Link;
use Dyryme\Utilities\RemoteClient;

/**
 * Link repository
 *
 * @package    Dyryme
 * @subpackage Repositories
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class LinkRepository {

	/**
	 * @var Link
	 */
	private $model;

	/**
	 * @var RemoteClient
	 */
	private $remoteClient;


	/**
	 * @param Link         $model
	 * @param RemoteClient $remoteClient
	 */
	function __construct(
		Link $model,
		RemoteClient $remoteClient
	) {
		$this->model        = $model;
		$this->remoteClient = $remoteClient;
	}


	/**
	 * @return \Illuminate\Pagination\Paginator
	 */
	public function getAllForList()
	{
		return $this->model->withTrashed()->with('hits')->orderBy('created_at', 'desc')->paginate(30);
	}


	/**
	 * Get top links ordered by hits
	 *
	 * @param int $count
	 *
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getTopLinks($count = 5)
	{
		return $this->model
			->join('hit_log', 'links.id', '=', 'hit_log.link_id')
			->select('links.id', 'hash', 'url', 'links.created_at', \DB::raw('count(*) as count'))
			->orderByRaw('count(*) desc')
			->groupBy('hit_log.link_id')
			->take($count)
			->get();
	}


	/**
	 * Get top creators grouped by remote address
	 *
	 * @param int $count
	 *
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getTopCreators($count = 5)
	{
		return $this->model->select('remoteAddress',
			\DB::raw('count(*) as count'))->groupBy('remoteAddress')->orderByRaw('count(*) desc')->take($count)->get();
	}


	/**
	 * Get a daily breakdown of links created between two dates
	 *
     * @param DateTime $start
     * @param DateTime $end
	 *
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
    public function getDailyLinkBreakdown(\DateTime $start, \DateTime $end)
    {
        return $this->model->select(\DB::raw('DATE(created_at) as date'), \DB::raw('COUNT(*) as links'))
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'asc')
            ->whereBetween('created_at', [ $start, $end, ])
            ->get();
    }


	/**
	 * Find a link by it's id
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Support\Collection|null|static
	 */
	public function lookupById($id)
	{
		return $this->model->withTrashed()->find($id);
	}


	/**
	 * Find a link by it's URL
	 *
	 * @param      $url
	 * @param bool $trashed
	 *
	 * @return mixed
	 */
	public function lookupByUrl($url, $trashed = false)
	{
		// If we should look for trashed URLs, too
		if ( $trashed )
		{
			return $this->model->withTrashed()->where('url', $url)->first();
		}

		return $this->model->where('url', $url)->first();
	}


	/**
	 * Find a link by it's hash
	 *
	 *
	 * @param $hash
	 *
	 * @return mixed
	 */
	public function lookupByHash($hash)
	{
		return $this->model->where('hash', $hash)->first();
	}


	/**
	 * Store a link record
	 *
	 * @param $input
	 *
	 * @return static
	 */
	public function store($input)
	{
		$input = array_merge($input, [
			'remoteAddress' => $this->remoteClient->getIpAddress(),
			'hostname'      => $this->remoteClient->getHostname(),
			'userAgent'     => $this->remoteClient->getUserAgent(),
		]);

		return $this->model->create($input);
	}


	/**
	 * Make a hash for a url
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public function makeHash($url)
	{
		// If the URL already exists, return the corresponding hash
		if ( ! is_null($link = $this->lookupByUrl($url, true)) )
		{
			if ( $link->trashed() )
			{
				$link->restore();
			}

			return [ $link->hash, true ];
		}

		// Ensure we don't duplicate hash values, causing validation errors
		do
		{
			$hash = \Str::random(5);
		} while ( ! is_null($this->lookupByHash($hash)));

		return [ $hash, false ];
	}


}
