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
	 * @var string
	 */
	protected $table = 'genres';
	/**
	 * @var bool
	 */
	public $timestamps = false;
	/**
	 * @var array
	 */
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
		return $this->morphedByMany('App\Models\Data\Book', 'element', 'elements_genres');
	}

	/**
	 * @return mixed
	 */
	public function films() {
		return $this->morphedByMany('App\Models\Data\Film', 'element', 'elements_genres');
	}

	/**
	 * @return mixed
	 */
	public function games() {
		return $this->morphedByMany('App\Models\Data\Game', 'element', 'elements_genres');
	}

	/**
	 * @return mixed
	 */
	public function albums() {
		return $this->morphedByMany('App\Models\Data\Album', 'element', 'elements_genres');
	}

	/**
	 * @return mixed
	 */
	public function memes() {
		return $this->morphedByMany('App\Models\Data\Meme', 'element', 'elements_genres');
	}

}
