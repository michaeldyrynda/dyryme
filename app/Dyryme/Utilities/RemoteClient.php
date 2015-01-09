<?php namespace Dyryme\Utilities;


class RemoteClient {

	protected $ipAddress;

	protected $hostname;

	protected $userAgent;


	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->setIpAddress();
		$this->setHostname();
		$this->setUserAgent();
	}


	/**
	 * @return mixed
	 */
	public function getIpAddress()
	{
		return $this->ipAddress;
	}


	/**
	 * @return mixed
	 */
	public function getHostname()
	{
		return $this->hostname;
	}


	/**
	 * @return mixed
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}


	/**
	 * Set the IP address
	 */
	private function setIpAddress()
	{
		$remote = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? array_pop(explode(',',
			$_SERVER['HTTP_X_FORWARDED_FOR'])) : $_SERVER['REMOTE_ADDR'];

		$this->ipAddress = ip2long($remote) !== false ? ip2long($remote) : null;
	}


	/**
	 * Set the hostname
	 */
	private function setHostname()
	{
		$ipAddress = long2ip($this->ipAddress);
		$hostname  = gethostbyaddr($ipAddress);

		$this->hostname = ( $hostname === false || $hostname == $ipAddress ) ? null : $hostname;
	}


	/**
	 * Set the user agent
	 */
	private function setUserAgent()
	{
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?: null;
	}


}
