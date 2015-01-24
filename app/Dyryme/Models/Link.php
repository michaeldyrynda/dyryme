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

	protected $fillable = [ 'hash', 'url', 'page_title', 'screenshot', 'thumbnail', 'remoteAddress', 'hostname', 'userAgent' ];


	public static function boot()
	{
		parent::boot();

		self::creating(function ($model)
		{
			$model->user_id = \Auth::check() ? \Auth::id() : null;
		});
	}


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
		$this->attributes['remoteAddress'] = ip2long($value) ?: null;
	}


	/**
	 * @param $url
	 */
	public function setUrlAttribute($url)
	{
		if ( ! starts_with($url, 'http://') && ! starts_with($url, 'https://') )
		{
			$this->attributes['url'] = sprintf('http://%s', $url);
		}
		else
		{
			$this->attributes['url'] = $url;
		}
	}


	/**
	 * @param $screenshot
	 */
	public function setScreenshotAttribute($screenshot)
	{
		$this->attributes['screenshot'] = trim($screenshot) !== '' ? $screenshot : null;
	}


	/**
	 * @param $thumbnail
	 */
	public function setThumbnailAttribute($thumbnail)
	{
		$this->attributes['thumbnail'] = trim($thumbnail) !== '' ? $thumbnail : null;
	}


}
