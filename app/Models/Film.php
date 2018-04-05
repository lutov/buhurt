<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

class Film extends Eloquent  {
//class Film extends SleepingOwlModel  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'films';


	protected $morphClass = 'Film';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;
	
	protected $fillable = array('name', 'alt_name', 'description', 'year', 'length', 'verified');

	/**
	 * Отношение со сценаристами
	 */
	public function screenwriters()
	{
		return $this->belongsToMany('App\Models\Person', 'screenwriters_films', 'film_id', 'person_id');
	}


	/**
	 * Отношение со продюссерами
	 */
	public function producers()
	{
		return $this->belongsToMany('App\Models\Person', 'producers_films', 'film_id', 'person_id');
	}


	/**
	 * Отношение со продюссерами
	 */
	public function directors()
	{
		return $this->belongsToMany('App\Models\Person', 'directors_films', 'film_id', 'person_id');
	}


	/**
	 * Отношение со продюссерами
	 */
	public function actors()
	{
		return $this->belongsToMany('App\Models\Person', 'actors_films', 'film_id', 'person_id');
	}


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
	public function countries()
	{
		return $this->belongsToMany('App\Models\Country', 'countries_films', 'film_id', 'country_id');
	}

	/*
	public function getValidationRules()
	{
		
	}
	*/
}
