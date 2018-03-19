<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'achievements';

	/**
	 * Отношение с
	 */
	public function users()
	{
		return $this->belongsToMany('App\Models\User', 'achievements_users', 'achievement_id', 'user_id');
	}

}
