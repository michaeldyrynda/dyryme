<?php namespace Dyryme\Listeners;

use Dyryme\Models\Link;

/**
 * Link observer
 *
 * @package    Dyryme
 * @subpackage Listeners
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class LinkForceDeletingListener {

	/**
	 * @param $link
	 */
	public function handle(Link $link)
	{
		// Delete the screenshot if it exists
		if ( ! is_null($link->screenshot) && \File::exists($link->screenshot) )
		{
			\File::delete($link->screenshot);
		}

		// Delete the thumbnail if it exists
		if ( ! is_null($link->thumbnail) && \File::exists($link->thumbnail) )
		{
			\File::delete($link->thumbnail);
		}
	}


}
