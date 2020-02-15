<?php namespace App\Models\Data;

use App\Traits\CollectionsTrait;
use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\RatesTrait;
use App\Traits\SectionTrait;
use App\Traits\WantedTrait;
use Eloquent;

/**
 * @property int id
 */
class Film extends Eloquent  {

	use SectionTrait;
	use GenresTrait;
	use CollectionsTrait;
	use RatesTrait;
	use CommentsTrait;
	use WantedTrait;

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
	 * @return mixed
	 */
	public function screenwriters() {

		return $this->belongsToMany('App\Models\Data\Person', 'screenwriters_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function producers() {

		return $this->belongsToMany('App\Models\Data\Person', 'producers_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function directors() {

		return $this->belongsToMany('App\Models\Data\Person', 'directors_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function actors() {

		return $this->belongsToMany('App\Models\Data\Person', 'actors_films', 'film_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function countries() {

		return $this->belongsToMany('App\Models\Data\Country', 'countries_films', 'film_id', 'country_id');

	}

}
