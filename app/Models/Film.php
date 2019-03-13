<?php namespace App\Models;

use App\Models\Helpers\SectionsHelper;
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
	 * @return string
	 */
	public function section() {

		return SectionsHelper::getSection($this->table);

	}

	/**
	 * @return mixed
	 */
	public function screenwriters() {

		return $this->belongsToMany('App\Models\Person', 'screenwriters_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function producers() {

		return $this->belongsToMany('App\Models\Person', 'producers_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function directors() {

		return $this->belongsToMany('App\Models\Person', 'directors_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function actors() {

		return $this->belongsToMany('App\Models\Person', 'actors_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function genres() {

		return $this->morphMany('App\Models\ElementGenre', 'element');

	}

	/**
	 * @return mixed
	 */
	public function collections() {

		return $this->morphMany('App\Models\ElementCollection', 'element');

	}

	/**
	 * @return mixed
	 */
	public function rates() {

		return $this->morphMany('App\Models\Rate', 'element');

	}

	/**
	 * @return mixed
	 */
	public function comments() {

		return $this->morphMany('App\Models\Comment', 'element');

	}

	/**
	 * @return mixed
	 */
	public function wanted() {

		return $this->morphMany('App\Models\Wanted', 'element');

	}

	/**
	 * @return mixed
	 */
	public function not_wanted() {

		return $this->morphMany('App\Models\NotWanted', 'element');
	}

	/**
	 * @return mixed
	 */
	public function countries() {
		return $this->belongsToMany('App\Models\Country', 'countries_films', 'film_id', 'country_id');
	}

}
