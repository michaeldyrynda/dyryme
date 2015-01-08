<?php namespace Dyryme\Validators;

use Illuminate\Validation\Validator;

/**
 * Base validation class
 *
 * @package    Dyryme
 * @subpackage Validators
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
abstract class AbstractValidator {

	protected $validator;


	/**
	 * @param Validator $validator
	 */
	function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}


	/**
	 * @return bool
	 * @throws ValidationFailedException
	 */
	public function fire($input)
	{
		$validator = \Validator::make($input, static::rules);

		if ( $validator->fails )
		{
			throw new ValidationFailedException($validator->messages());
		}

		return true;
	}

}
