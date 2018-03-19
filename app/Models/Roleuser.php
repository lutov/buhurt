<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roleuser extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'role_user';

	public function role()
	{
		return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id', 'role_id');
	}
}
