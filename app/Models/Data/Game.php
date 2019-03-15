<?php namespace App\Models\Data;

use App\Traits\CollectionsTrait;
use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\IsWantedTrait;
use App\Traits\RatesTrait;
use App\Traits\SectionTrait;
use App\Traits\WantedTrait;
use Eloquent;

/**
 * @property int id
 */
class Game extends Eloquent  {

	use SectionTrait;
	use GenresTrait;
	use CollectionsTrait;
	use RatesTrait;
	use CommentsTrait;
	use WantedTrait;
	use IsWantedTrait;

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
	public function developer() {

		return $this->belongsToMany('App\Models\Data\Company', 'developers_games', 'game_id', 'company_id');

	}

	/**
	 * @return mixed
	 */
	public function publisher() {

		return $this->belongsToMany('App\Models\Data\Company', 'publishers_games', 'game_id', 'company_id');

	}

}
