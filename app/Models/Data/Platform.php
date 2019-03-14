<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Eloquent;

class Platform extends Eloquent {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'platforms';

	public $timestamps = false;

	protected $fillable = array('name');

	/**
	 * @return mixed
	 */
	public function games() {

		return $this->belongsToMany('App\Models\Data\Game', 'platforms_games', 'platform_id', 'game_id');

	}

}
