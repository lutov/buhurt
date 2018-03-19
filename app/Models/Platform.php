<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

//class Platform extends SleepingOwlModel  {
class Platform extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'platforms';

	public $timestamps = false;

	protected $fillable = array('name');

	/**
	 * Отношение с
	 */
	public function games()
	{
		return $this->belongsToMany('App\Models\Game', 'platforms_games', 'platform_id', 'game_id');
	}

}
