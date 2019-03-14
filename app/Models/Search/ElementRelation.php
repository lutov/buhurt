<?php namespace App\Models\Search;

use Eloquent;

class ElementRelation extends Eloquent {

	protected $table = 'elements_relations';

	public $timestamps = false;

	/**
	 * @return mixed
	 */
	public function books() {

		return $this->hasMany('App\Models\Data\Book', 'id', 'element_id');

	}

	/**
	 * @return mixed
	 */
	public function films() {

		return $this->hasMany('App\Models\Data\Film', 'id', 'element_id');

	}

	/**
	 * @return mixed
	 */
	public function games() {

		return $this->hasMany('App\Models\Data\Game', 'id', 'element_id');

	}

	/**
	 * @return mixed
	 */
	public function albums() {

		return $this->hasMany('App\Models\Data\Album', 'id', 'element_id');

	}

	/**
	 * @return mixed
	 */
	public function memes() {

		return $this->hasMany('App\Models\Data\Meme', 'id', 'element_id');

	}

	/**
	 * @return mixed
	 */
	public function relation() {

		return $this->belongsTo('App\Models\Search\Relation');

	}

}