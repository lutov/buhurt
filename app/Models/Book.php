<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

class Book extends Eloquent  {
//class Book extends SleepingOwlModel  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'books';

	protected $morphClass = 'Book';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'alt_name', 'description', 'year', 'verified');


	/**
	 * Отношение с комментариями
	 */
	public function comments()
	{
		return $this->morphMany('App\Models\Comment', 'element');
	}


	/**
	 * Отношение с оценками
	 */
	public function rates()
	{
		return $this->morphMany('App\Models\Rate', 'element');
	}


	/**
	 * Отношение с писателями
	 */
	public function writers()
	{
		return $this->belongsToMany('App\Models\Person', 'writers_books', 'book_id', 'person_id');
	}


	/**
	 * Отношение с писателями
	 */
	public function publishers()
	{
		return $this->belongsToMany('App\Models\Company', 'publishers_books', 'book_id', 'company_id');
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
	 * Отношение с желаемым
	 */
	public function wanted()
	{
		return $this->morphMany('App\Models\Wanted', 'element');
	}

}
