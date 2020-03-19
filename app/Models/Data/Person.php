<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Eloquent;

/**
 * @property string name
 * @property string description
 */
class Person extends Eloquent {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'persons';

	protected $morphClass = 'Person';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'bio');

	public bool $verification = false;

	/**
	 * @return mixed
	 */
	public function books() {

		return $this->belongsToMany('App\Models\Data\Book', 'writers_books', 'person_id', 'book_id');

	}

	/**
	 * @return mixed
	 */
	public function directions() {

		return $this->belongsToMany('App\Models\Data\Film', 'directors_films', 'person_id', 'film_id');

	}

	/**
	 * @return mixed
	 */
	public function screenplays() {

		return $this->belongsToMany('App\Models\Data\Film', 'screenwriters_films', 'person_id', 'film_id');

	}

	/**
	 * @return mixed
	 */
	public function productions() {

		return $this->belongsToMany('App\Models\Data\Film', 'producers_films', 'person_id', 'film_id');

	}

	/**
	 * @return mixed
	 */
	public function roles() {

		return $this->belongsToMany('App\Models\Data\Film', 'actors_films', 'person_id', 'film_id');

	}

}
