<?php namespace App\Models\Search;

use Eloquent;

class Relation extends Eloquent {

	/**
	 * @var string
	 */
	protected $table = 'relations';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = array('name','alt_name');

	protected $visible = ['id', 'name'];

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

}