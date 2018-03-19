<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

//class Collection extends SleepingOwlModel  {
class Collection extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'collections';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	public $timestamps = false;

	protected $fillable = array('name','alt_name', 'description');

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

	/**
	 * Отношение с
	 */
	public function films()
	{
		return $this->morphMany('App\Models\Film', 'element');
	}

	/**
	 * Отношение с
	 */
	public function games()
	{
		return $this->morphMany('App\Models\Game', 'element');
	}


}
