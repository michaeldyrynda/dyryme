<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	protected $fillable = [ 'username', 'password', 'superuser', ];


	public static function boot()
	{
		parent::boot();

		self::created(function ($model)
		{
			$model->groups()->sync([ 2, ]);
		});
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function links()
	{
		return $this->hasMany('Dyryme\Models\Link');
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function groups()
	{
		return $this->belongsToMany('Dyryme\Models\AclGroup', 'acl_user_groups', 'user_id', 'group_id');
	}


	/**
	 * Determine if this user is a superuser
	 *
	 * @return mixed
	 */
	public function isSuperUser()
	{
		return $this->superuser;
	}


	/**
	 * Get all this user's permissions
	 *
	 * @return array
	 */
	public function getPermissions()
	{
		$permissions = [];

		foreach ($this->load('groups', 'groups.permissions')->groups as $group)
		{
			foreach ($group->permissions as $permission)
			{
				$permissions[] = $permission->ident;
			}
		}

		return $permissions;
	}


	/**
	 * Determine if this user has the given permission
	 *
	 * @param $ident
	 *
	 * @return bool
	 */
	public function hasPermission($ident)
	{
		return $this->isSuperUser() || in_array($ident, $this->getPermissions());
	}


}
