<?php namespace App\Models\Search;

use Eloquent;

class Poster extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posters';

	protected $morphClass = 'Poster';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name');

}
