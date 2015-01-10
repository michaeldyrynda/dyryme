<?php namespace Dyryme\Validators;

/**
 * Generic validation failed exception
 *
 * @package    Dyryme
 * @subpackage Validators
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */

class ValidationFailedException extends \Exception {

	protected $errors;


	/**
	 * @param $errors
	 */
	function __construct($errors)
	{
		$this->errors = $errors;
	}


	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}


}
