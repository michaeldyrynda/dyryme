<?php namespace Dyryme\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Link model
 *
 * @package    Dyryme
 * @subpackage Models
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class Link extends Eloquent {

	use SoftDeletingTrait;

	protected $fillable = [ 'hash', 'url', 'remoteAddress', 'hostname', 'userAgent' ];


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function hits()
	{
		return $this->hasMany('Dyryme\Models\HitLog');
	}


	/**
	 * @param $value
	 *
	 * @return null
	 */
	public function getRemoteAddressAttribute($value)
	{
		return long2ip($value) ?: null;
	}


	/**
	 * @param $value
	 */
	public function setRemoteAddressAttribute($value)
	{
		$this->parameters['remoteAddress'] = ip2long($value) ?: null;
	}


}
