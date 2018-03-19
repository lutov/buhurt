<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

//class Country extends SleepingOwlModel  {
class Country extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'countries';

	protected $fillable = array('name');
	
	public $timestamps = false;

	/**
	 * Отношение с
	 */
	public function films()
	{
		return $this->belongsToMany('App\Models\Film', 'countries_films', 'country_id', 'film_id');
	}

}
