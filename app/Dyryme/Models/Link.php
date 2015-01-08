<?php namespace Dyryme\Models;

use Eloquent;

/**
 * Link model
 *
 * @package    Dyryme
 * @subpackage Models
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class Link extends Eloquent {

	protected $fillable = [ 'hash', 'url', 'remoteAddress', 'hostname', 'userAgent' ];

}
