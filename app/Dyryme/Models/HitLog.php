<?php namespace Dyryme\Models;

use Eloquent;

/**
 * Hit log model
 *
 * @package    Dyryme
 * @subpackage Models
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class HitLog extends Eloquent {

	protected $fillable = [ 'link_id', 'remoteAddress', 'hostname', 'userAgent', 'referer', ];

	protected $table = 'hit_log';


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function link()
	{
		return $this->belongsTo('Dyryme\Models\Link');
	}


}
