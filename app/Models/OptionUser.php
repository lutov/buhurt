<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionUser extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'options_users';

	public $timestamps = false;

	/**
	 * Отношение с
	 */
	/*
	public function users()
	{
		return $this->belongsToMany('User', 'achievements_users', 'achievement_id', 'user_id');
	}
	*/

}
