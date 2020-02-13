<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Eloquent;

/**
 * @property mixed name
 * @property string description
 * @property int id
 */
class Collection extends Eloquent {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'collections';

	protected $morphClass = 'Collection';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	public $timestamps = false;

	protected $fillable = array('name','alt_name', 'description');

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

		return $this->morphedByMany('App\Models\Data\Book', 'element');

	}

	/**
	 * @return mixed
	 */
	public function films() {

		return $this->morphedByMany('App\Models\Data\Film', 'element');

	}

	/**
	 * @return mixed
	 */
	public function games() {

		return $this->morphedByMany('App\Models\Data\Game', 'element');

	}

	/**
	 * @return mixed
	 */
	public function albums() {

		return $this->morphdByMany('App\Models\Data\Album', 'element');

	}

	/**
	 * @return mixed
	 */
	public function memes() {

		return $this->morphedByMany('App\Models\Data\Meme', 'element');

	}


}
