<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

//class Person extends SleepingOwlModel {
class Person extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'persons';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'bio');

	/**
	 * Отношение с книгами
	 */
	public function books()
	{
		return $this->belongsToMany('App\Models\Book', 'writers_books', 'person_id', 'book_id');
	}

	/**
	 * Отношение с фильмами
	 */
	public function directions()
	{
		return $this->belongsToMany('App\Models\Film', 'directors_films', 'person_id', 'film_id');
	}
	
	/**
	 * Отношение со сценариями
	 */
	public function screenplays()
	{
		return $this->belongsToMany('App\Models\Film', 'screenwriters_films', 'person_id', 'film_id');
	}
	
	/**
	 * Отношение с фильмами
	 */
	public function productions()
	{
		return $this->belongsToMany('App\Models\Film', 'producers_films', 'person_id', 'film_id');
	}

	/**
	 * Отношение с фильмами
	 */
	public function actions()
	{
		return $this->belongsToMany('App\Models\Film', 'actors_films', 'person_id', 'film_id');
	}

}
