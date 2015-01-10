<?php namespace Dyryme\Repositories;

use Dyryme\Models\HitLog;
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
	 * @return static
	 */
	public function store()
	{
		$input = [
			'remoteAddress' => $this->remoteClient->getIpAddress(),
			'hostname'      => $this->remoteClient->getHostname(),
			'userAgent'     => $this->remoteClient->getUserAgent(),
			'referrer'      => $this->remoteClient->getReferrer(),
		];

		return $this->model->create($input);
	}

}
