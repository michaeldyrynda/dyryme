<?php namespace Dyryme\Utilities;


class RemoteClient {

	/**
	 * @var string
	 */
	private $ipAddress;

	/**
	 * @var string
	 */
	private $hostname;

	/**
	 * @var string
	 */
	private $userAgent;

	/**
	 * @var string
	 */
	private $referer;


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
	 * @return mixed
	 */
	public function getReferer()
	{
		return $this->referer;
	}


	/**
	 * Set the IP address
	 */
	private function setIpAddress()
	{
		$this->ipAddress = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? array_pop(explode(',',
			$_SERVER['HTTP_X_FORWARDED_FOR'])) : $_SERVER['REMOTE_ADDR'];
	}


	/**
	 * Set the hostname
	 */
	private function setHostname()
	{
		$hostname  = gethostbyaddr($this->ipAddress);

		$this->hostname = ( $hostname === false || $hostname == $ipAddress ) ? null : $hostname;
	}


	/**
	 * Set the user agent
	 */
	private function setUserAgent()
	{
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?: null;
	}


	/**
	 * Set the referer
	 */
	public function setReferrer()
	{
		$this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
	}


}
