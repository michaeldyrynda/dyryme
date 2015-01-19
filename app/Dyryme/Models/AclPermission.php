<?php namespace Dyryme\Models;

class AclPermission extends \Eloquent {

	protected $table = 'acl_permissions';

	protected $fillable = [ 'ident', 'description', ];

	public $timestamps = false;


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function groups()
	{
		return $this->belongsToMany('Dyryme\Models\AclGroup', 'acl_group_permissions');
	}


	/**
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->attributes['ident'];
	}

}
