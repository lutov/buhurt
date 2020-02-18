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

	private function getByType(string $type) {

		return $this->where('element_type', $type)->get();

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
