<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Eloquent;

/**
 * @property mixed name
 * @property string element_type
 * @property int id
 */
class Genre extends Eloquent {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'genres';

	public $timestamps = false;

	protected $fillable = array('name', 'description');

	/**
	 * @return mixed
	 */
	public function element() {

		return $this->morphTo();

	}

	/**
	 * @return mixed
	 */
	public function books() {

		return $this->morphMany('App\Models\Data\Book', 'element');

	}

	/**
	 * @return mixed
	 */
	public function films() {

		return $this->morphMany('App\Models\Data\Film', 'element');

	}

	/**
	 * @return mixed
	 */
	public function games() {

		return $this->morphMany('App\Models\Data\Game', 'element');

	}

	/**
	 * @return mixed
	 */
	public function albums() {

		return $this->morphMany('App\Models\Data\Album', 'element');

	}

	/**
	 * @return mixed
	 */
	public function memes() {

		return $this->morphMany('App\Models\Data\Meme', 'element');

	}

}
