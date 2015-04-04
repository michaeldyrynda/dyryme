<?php namespace Dyryme\Utilities;

class RemoteClient {

	/**
	 * @return mixed
	 */
	public function getIpAddress()
	{
		return \Request::getClientIp();
	}


	/**
	 * @return mixed
	 */
	public function getHostname()
	{
		$hostname = gethostbyaddr($this->getIpAddress());

		return ( $hostname !== false && $hostname !== $this->getIpAddress() ) ? $hostname : null;
	}


	/**
	 * @return mixed
	 */
	public function getUserAgent()
	{
		return \Request::server('HTTP_USER_AGENT');
	}


	/**
	 * @return mixed
	 */
	public function getReferer()
	{
		return \Request::server('HTTP_REFERER');
	}


	/**
	 * Determine if visitor is Hitler (the irc.jewoven.com bot)
	 *
	 * @return bool
	 */
	public function isHitler()
	{
		return $this->getIpAddress() == \Config::get('dyryme.hitler.ip') || $this->getHostname() == \Config::get('dyryme.hitler.hostname');
	}


}
