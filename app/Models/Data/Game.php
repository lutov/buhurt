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
class Game extends Model {

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
	protected $table = 'games';

	protected $morphClass = 'Game';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'alt_name', 'description', 'year', 'verified');

	// protected $hidden = ['created_at', 'updated_at'];
	protected $with = ['genres', 'collections', 'platforms', 'developers', 'games_publishers'];
	protected $appends = ['cover', 'rating', 'simple_relations'];

	public bool $verification = true;
    public bool $has_alt_name = true;
    public bool $has_description = true;
    public bool $has_year = true;

	/**
	 * @return mixed
	 */
	public function platforms() {

		return $this->belongsToMany('App\Models\Data\Platform', 'platforms_games', 'game_id', 'platform_id');

	}

	/**
	 * @return mixed
	 */
	public function developers() {

		return $this->belongsToMany('App\Models\Data\Company', 'developers_games', 'game_id', 'company_id');

	}

	/**
	 * @return mixed
	 */
	public function games_publishers() {

		return $this->belongsToMany('App\Models\Data\Company', 'publishers_games', 'game_id', 'company_id');

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
