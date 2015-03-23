<?php namespace Dyryme\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {
	use Authenticatable, CanResetPassword;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', 'superuser',];
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];


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
	 * @param $password
	 */
	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = Hash::make($password);
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
