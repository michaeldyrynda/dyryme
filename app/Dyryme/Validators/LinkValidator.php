<?php namespace Dyryme\Validators;

/**
 * Link validator
 *
 * @package    Dyryme
 * @subpackage Validators
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */

class LinkValidator extends AbstractValidator {

	protected static $rules = [
		'hash' => [ 'required', 'unique:links,hash', ],
		'url'  => [ 'required', 'active_url', 'unique:links,url', 'no_recursion', ],
	];

}
