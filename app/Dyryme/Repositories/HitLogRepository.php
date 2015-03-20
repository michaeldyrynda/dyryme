<?php namespace Dyryme\Repositories;

use Carbon\Carbon;
use Dyryme\Models\HitLog;
use Dyryme\Models\Link;
use Dyryme\Utilities\RemoteClient;

class HitLogRepository {

	/**
	 * @var
	 */
	private $model;

	/**
	 * @var
	 */
	private $remoteClient;


	/**
	 * @param HitLog       $model
	 * @param RemoteClient $remoteClient
	 */
	function __construct(HitLog $model, RemoteClient $remoteClient)
	{
		$this->model        = $model;
		$this->remoteClient = $remoteClient;
	}


	/**
	 * @param Link $link
	 *
	 * @return static
	 */
	public function store(Link $link)
	{
		$hit = $this->model->create([
			'remoteAddress' => $this->remoteClient->getIpAddress(),
			'hostname'      => $this->remoteClient->getHostname(),
			'userAgent'     => $this->remoteClient->getUserAgent(),
			'referer'       => $this->remoteClient->getReferer(),
		]);

		return $link->hits()->save($hit);
	}


	/**
	 * Get a daily breakdown of link hits between two dates
	 *
	 * @param \DateTime $start
	 *
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getDailyBreakdown(\DateTime $start)
	{
		return $this->model->select(\DB::raw('DATE(created_at) as date'), \DB::raw('COUNT(*) as hits'))
			->groupBy(\DB::raw('DATE(created_at)'))
			->orderBy('created_at', 'asc')
			->where('created_at', '>', $start)
			->get();
	}


	/**
	 * Return a count of how many hits a link has received within the last minute from a given remote address
	 *
	 * @param $linkId
	 *
	 * @return mixed
	 */
	public function countByAddress($linkId)
	{
		$start = Carbon::now()->subMinutes(1);

		return $this->model->select([ \DB::raw('COUNT(*) as count'), ])
			->where('link_id', $linkId)
			->where('created_at', '>', $start)
			->where('remoteAddress', ip2long($this->remoteClient->getIpAddress()))
			->first()
			->count;
	}


	/**
	 * Return the number of links that were referred to us.
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getReferred($id)
	{
		return $this->model->selectRaw('COUNT(*) as count')->whereNotNull('referer')->where('id', $id)->first()->count;
	}


}
