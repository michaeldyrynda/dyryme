<?php namespace Dyryme\Repositories;

use Dyryme\Models\Link;

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
	 * @param Link $model
	 */
	function __construct(Link $model)
	{
		$this->model = $model;
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


}
