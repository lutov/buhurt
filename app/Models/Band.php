<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

//class Band extends SleepingOwlModel {
class Band extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bands';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'bio');

	public function albums()
	{
		return $this->belongsToMany('App\Models\Album', 'bands_albums', 'band_id', 'album_id');
	}

	/**
	 * Отношение с книгами
	 */
	public function members()
	{
		return $this->belongsToMany('App\Models\Person', 'bands_persons', 'band_id', 'person_id');
	}

}
