<?php namespace Dyryme\Handlers;

use Dyryme\Repositories\LinkRepository;
use Dyryme\Utilities\RemoteClient;

/**
 * Link handler
 *
 * @package    Dyryme
 * @subpackage Handlers
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class LinkHandler {

	/**
	 * @var LinkRepository
	 */
	private $linkRepository;

	/**
	 * @var RemoteClient
	 */
	private $remoteClient;


	/**
	 * @param LinkRepository $linkRepository
	 * @param RemoteClient   $remoteClient
	 */
	public function __construct(LinkRepository $linkRepository, RemoteClient $remoteClient)
	{
		$this->linkRepository = $linkRepository;
		$this->remoteClient   = $remoteClient;
	}


	/**
	 * @param array $input
	 *
	 * @return string
	 */
	public function make(array $input)
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

		if ( $this->needsHttp($input) )
		{
			$input['longUrl'] = sprintf('http://%s', $input['longUrl']);
		}

		list( $hash, $existing ) = $this->linkRepository->makeHash($input['longUrl']);

		if ( ! $existing )
		{
			$hash = $this->createNewLink($input, $hash);
		}

		return $hash;
	}


	/**
	 * @param array $input
	 * @param       $hash
	 *
	 * @return string
	 */
	public function createNewLink(array $input, $hash)
	{
		try
		{
			\Event::fire('link.creating', [ [ 'url' => $input['longUrl'], 'hash' => $hash, ] ]);

			$link = $this->linkRepository->store([
				'url'  => $input['longUrl'],
				'hash' => $hash,
			]);

			\Queue::push('Dyryme\Queues\LinkTitleHandler', [ 'id' => $link->id, ]);
			\Queue::push('Dyryme\Queues\ScreenshotHandler', [ 'id' => $link->id, ]);

			return $link->hash;
		}
		catch (ValidationFailedException $e)
		{
			return \Redirect::route('create')->withErrors($e->getErrors())->withInput();
		}
	}


	/**
	 * @param array $input
	 *
	 * @return bool
	 */
	private function needsHttp(array $input)
	{
		return ! starts_with($input['longUrl'], [ 'http://', 'https://', ]);
	}

}
