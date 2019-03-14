<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Roleuser extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'role_user';

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function role() {

		return $this->belongsToMany('App\Models\User\Role', 'role_user', 'user_id', 'role_id');

	}
}
