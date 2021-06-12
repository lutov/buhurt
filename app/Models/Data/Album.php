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
 */
class Album extends Model {

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
	protected $table = 'albums';

	/**
	 * @var string
	 */
	protected $morphClass = 'Album';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'description', 'year', 'verified');

	protected $hidden = ['alt_name', 'description'];
	protected $with = ['bands', 'tracks'];
	protected $appends = ['cover', 'rating', 'simple_relations'];

	public bool $verification = true;
    public bool $has_description = true;
    public bool $has_year = true;

	/**
	 * @return mixed
	 */
	public function bands() {

		return $this->belongsToMany('App\Models\Data\Band', 'bands_albums', 'album_id', 'band_id');

	}

	/**
	 * @return mixed
	 */
	public function tracks() {

		return $this->belongsToMany('App\Models\Data\Track', 'albums_tracks');

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
