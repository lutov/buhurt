<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

class Game extends Eloquent  {
//class Game extends SleepingOwlModel  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'games';

	protected $morphClass = 'Game';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'alt_name', 'description', 'year', 'verified');

	/**
	 * Отношение с жанрами
	 */
	public function genres()
	{
		return $this->morphMany('App\Models\ElementGenre', 'element');
	}


	/**
	 * Отношение с коллекциями
	 */
	public function collections()
	{
		return $this->morphMany('App\Models\ElementCollection', 'element');
	}


	/**
	 * Отношение с оценками
	 */
	public function rates()
	{
		return $this->morphMany('App\Models\Rate', 'element');
	}

	/**
	 * Отношение с комментариями
	 */
	public function comments()
	{
		return $this->morphMany('App\Models\Comment', 'element');
	}

	/**
	 * Отношение с желаемым
	 */
	public function wanted()
	{
		return $this->morphMany('App\Models\Wanted', 'element');
	}


	/**
	 * Отношение с нежелаемым
	 */
	public function not_wanted()
	{
		return $this->morphMany('App\Models\NotWanted', 'element');
	}

	/**
	 * Отношение с
	 */
	public function platforms()
	{
		return $this->belongsToMany('App\Models\Platform', 'platforms_games', 'game_id', 'platform_id');
	}

	/**
	 * Отношение с
	 */
	public function developer()
	{
		return $this->belongsToMany('App\Models\Company', 'developers_games', 'game_id', 'company_id');
	}

	/**
	 * Отношение с
	 */
	public function publisher()
	{
		return $this->belongsToMany('App\Models\Company', 'publishers_games', 'game_id', 'company_id');
	}

}
