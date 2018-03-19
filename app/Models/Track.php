<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

class Track extends Eloquent  {
//class Track extends SleepingOwlModel  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tracks';

	protected $morphClass = 'Track';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'length', 'order', 'album_id');



}
