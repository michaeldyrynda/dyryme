<?php namespace Dyryme\Queues;

use Dyryme\Exceptions\PageTitleNotFoundException;
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
	 * @throws PageTitleNotFoundException
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

		$title = $this->getTitle($data['url']);

		if ( trim($title) == '' )
		{
			/*
			 * HTTP connection may have timed out or page has no title, throwing an exception will release this job back
			 * to the queue to be tried again, and tidied up as needed.
			 */
			throw new PageTitleNotFoundException;
		}

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
	 * @param $url
	 *
	 * @return string
	 */
	private function getTitle($url)
	{
		$body    = $this->getUrlBody($url);
		$crawler = new Crawler($body);

		return $crawler->filterXPath('//title')->text();
	}

}
