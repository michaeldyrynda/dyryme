<?php namespace Dyryme\Validators;

use Dyryme\Exceptions\ValidationFailedException;
use Illuminate\Validation\Factory;

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


	function __construct(Factory $validator)
	{
		$this->validator = $validator;
	}


	/**
	 * @param $input
	 *
	 * @return bool
	 * @throws ValidationFailedException
	 */
	public function fire($input)
	{
		$validator = $this->validator->make($input, static::$rules);

		if ( $validator->fails() )
		{
			throw new ValidationFailedException($validator->messages());
		}

		return true;
	}

}
