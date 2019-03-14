<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'achievements';

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users() {

		return $this->belongsToMany('App\Models\User\User', 'achievements_users', 'achievement_id', 'user_id');

	}

}
