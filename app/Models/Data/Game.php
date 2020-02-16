<?php namespace App\Models\Data;

use App\Traits\CollectionsTrait;
use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\RatesTrait;
use App\Traits\RelationsTrait;
use App\Traits\SectionTrait;
use App\Traits\WantedTrait;
use Eloquent;

/**
 * @property int id
 */
class Game extends Eloquent  {

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

}
