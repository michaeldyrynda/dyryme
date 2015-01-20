<?php namespace Dyryme\Repositories;

/**
 * User repository
 *
 * @package    Dyryme
 * @subpackage Repositories
 * @copyright  2015 IATSTUTI
 * @author     Michael Dyrynda <michael@iatstuti.net>
 */
class UserRepository {

	/**
	 * @var \User
	 */
	protected $model;


	/**
	 * @param \User $model
	 */
	function __construct(\User $model)
	{
		$this->model = $model;
	}


	/**
	 * Create a new user
	 *
	 * @param array $input
	 *
	 * @return static
	 */
	public function store(array $input)
	{
		return $this->model->create($input);
	}


}
