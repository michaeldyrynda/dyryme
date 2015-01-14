<?php namespace Dyryme\Repositories;

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
	 * @param DateTime $start
	 * @param DateTime $end
	 *
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getDailyBreakdown(\DateTime $start, \DateTime $end)
	{
		return $this->model->select(\DB::raw('DATE(created_at) as date'), \DB::raw('COUNT(*) as hits'))
			->groupBy(\DB::raw('DATE(created_at)'))
			->orderBy('created_at', 'asc')
			->whereBetween('created_at', [ $start, $end, ])
			->get();
	}

}
