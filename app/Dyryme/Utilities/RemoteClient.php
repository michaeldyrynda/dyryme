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


}
