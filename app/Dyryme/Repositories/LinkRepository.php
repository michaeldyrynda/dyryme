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
	 * Find a link by it's URL
	 *
	 * @param $url
	 *
	 * @return mixed
	 */
	public function lookupByUrl($url)
	{
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
	 * If the URL already exists, return the corresponding hash
	 * Ensure we don't duplicate hash values, causing validation errors, before returning a hash
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public function makeHash($url)
	{
		if ( ! is_null($link = $this->lookupByUrl($url)) )
		{
			return [ $link->hash, true ];
		}

		do
		{
			$hash = \Str::random(5);
		} while ( ! is_null($this->lookupByHash($hash)));

		return [ $hash, false ];
	}


}
