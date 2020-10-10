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
	 * @var string
	 */
	protected $table = 'collections';
	/**
	 * @var string
	 */
	protected $morphClass = 'Collection';
	/**
	 * @var bool
	 */
	public $timestamps = false;
	/**
	 * @var array
	 */
	protected $fillable = array('name','alt_name', 'description');

	protected $visible = ['id', 'name'];

	public bool $verification = false;
    public bool $has_description = true;

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
		return $this->morphedByMany('App\Models\Data\Book', 'element', 'elements_collections');
	}

	/**
	 * @return mixed
	 */
	public function films() {
		return $this->morphedByMany('App\Models\Data\Film', 'element', 'elements_collections');
	}

	/**
	 * @return mixed
	 */
	public function games() {
		return $this->morphedByMany('App\Models\Data\Game', 'element', 'elements_collections');
	}

	/**
	 * @return mixed
	 */
	public function albums() {
		return $this->morphedByMany('App\Models\Data\Album', 'element', 'elements_collections');
	}

	/**
	 * @return mixed
	 */
	public function memes() {
		return $this->morphedByMany('App\Models\Data\Meme', 'element', 'elements_collections');
	}

}