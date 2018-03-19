<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

//class Genre extends SleepingOwlModel {
class Genre extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'genres';

	public $timestamps = false;

	protected $fillable = array('name', 'description');

	public function element()
	{
		return $this->morphTo();
	}

	/**
	 * Отношение с
	 */
	public function books()
	{
		return $this->morphMany('App\Models\Book', 'element');
	}

}
