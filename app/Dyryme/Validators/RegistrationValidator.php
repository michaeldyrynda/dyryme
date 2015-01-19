<?php namespace Dyryme\Validators;

/**
 * Registration validator
 *
 * @package    Dyryme
 * @subpackage Validators
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */

class RegistrationValidator extends AbstractValidator {

	protected static $rules = [
		'email_address' => [ 'required', 'email', 'unique:users,username', ],
		'password'      => [ 'required', 'confirmed', ],
	];

}
