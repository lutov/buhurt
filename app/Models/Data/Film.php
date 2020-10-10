<?php namespace App\Models\Data;

use App\Helpers\ElementsHelper;
use App\Traits\CollectionsTrait;
use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\RatesTrait;
use App\Traits\RelationsTrait;
use App\Traits\SectionTrait;
use App\Traits\WantedTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property string alt_name
 */
class Film extends Model {

	use SectionTrait;
	use RatesTrait;
	use WantedTrait;
	use GenresTrait;
	use CollectionsTrait;
	use RelationsTrait;
	use CommentsTrait;

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

	// protected $hidden = ['created_at', 'updated_at'];
	protected $with = ['genres', 'collections', 'screenwriters', 'directors', 'producers', 'actors', 'countries'];
	protected $appends = ['cover', 'rating', 'simple_relations'];

	public bool $verification = true;
    public bool $has_alt_name = true;
    public bool $has_description = true;
    public bool $has_year = true;
    public bool $has_length = true;

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

	/**
	 * @param $value
	 * @return array
	 */
	public function getAltNameAttribute($value) {
		return explode('; ', $value);
	}

	/**
	 * @return int
	 */
	public function getCoverAttribute() {
		return ElementsHelper::getCover($this->table, $this->id);
	}

	/**
	 * @return array
	 */
	public function getRatingAttribute() {
		return ElementsHelper::countRating($this);
	}

}
