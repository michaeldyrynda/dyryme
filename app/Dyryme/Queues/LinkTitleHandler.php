<?php namespace Dyryme\Queues;

use Dyryme\Repositories\LinkRepository;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Link title queue handler
 *
 * @package    Dyryme
 * @subpackage Queues
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class LinkTitleHandler {

	/**
	 * @var LinkRepository
	 */
	private $linkRepository;

	/**
	 * @var Client
	 */
	private $client;


	/**
	 * @param LinkRepository $linkRepository
	 * @param Client         $client
	 */
	public function __construct(LinkRepository $linkRepository, Client $client)
	{
		$this->linkRepository = $linkRepository;
		$this->client         = $client;
	}


	/**
	 * @param $job
	 * @param $data
	 *
	 * @return bool
	 */
	public function fire($job, $data)
	{
		$link = $this->linkRepository->lookupById($data['id']);

		if ( $job->attempts() > 3 )
		{
			// Three failed attempts is enough, forget about this link...
			$job->delete();

			// ...it's probably dead
			$link->forceDelete();

			return true;
		}

		$body  = $this->getUrlBody($data['url']);
		$title = $this->getTitle($body);

		if ( $link->fill([ 'page_title' => $title, ])->save() )
		{
			$job->delete();

			return true;
		}

		$job->release();

		return false;
	}


	/**
	 * @param $url
	 *
	 * @return string
	 */
	private function getUrlBody($url)
	{
		$response = $this->client->get($url);

		return $response->getBody()->getContents();
	}


	/**
	 * @param $body
	 *
	 * @return string
	 */
	private function getTitle($body)
	{
		$crawler = new Crawler($body);

		return $crawler->filterXPath('//title')->text();
	}

}
