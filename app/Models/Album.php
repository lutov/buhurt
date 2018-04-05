<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

class Album extends Eloquent  {
//class Album extends SleepingOwlModel  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'albums';

	protected $morphClass = 'Album';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'description', 'year', 'verified');

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
	public function wanted() {

		return $this->morphMany('App\Models\Wanted', 'element');

	}


	/**
	 * Отношение с нежелаемым
	 */
	public function not_wanted() {

		return $this->morphMany('App\Models\NotWanted', 'element');

	}

	/**
	 * Отношение с
	 */
	public function bands() {

		return $this->belongsToMany('App\Models\Band', 'bands_albums', 'album_id', 'band_id');

	}

	public function tracks() {

		return $this->hasMany('App\Models\Track');

	}
}
