<?php namespace Dyryme\Controllers;

use Dyryme\Handlers\LinkHandler;
use Dyryme\Repositories\LinkRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Api Controller
 *
 * @package    Dyryme
 * @subpackage Controllers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class ApiController extends \BaseController {

	/**
	 * @var LinkRepository
	 */
	protected $linkRepository;


	/**
	 * @param LinkRepository $linkRepository
	 */
	function __construct(LinkRepository $linkRepository)
	{
		$this->linkRepository = $linkRepository;
	}


	/**
	 * @param $hash
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function lookupHash($hash)
	{
		if ( ! is_null($link = $this->linkRepository->lookupByHash($hash)) )
		{
			return \Response::json([
				'error'    => false,
				'message'  => sprintf('Found a link with hash %s', $hash),
				'response' => [
					'url'   => $link->url,
					'title' => $link->page_title,
					'hits'  => $link->hits->count(),
				],
			]);
		}
		else
		{
			return \Response::json([
				'error'   => true,
				'message' => sprintf('A link with hash %s could not be found', $hash),
			], 404);
		}
	}


}
