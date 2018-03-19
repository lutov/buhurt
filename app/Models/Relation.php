<?php namespace App\Models;

use Eloquent;

class Relation extends Eloquent {

	protected $table = 'relations';

	public $timestamps = false;

	protected $fillable = array('name','alt_name');

	public function element() {
		return $this->morphTo();
	}

	public function books() {
		return $this->morphMany('App\Models\Book', 'element');
	}

	public function films() {
		return $this->morphMany('App\Models\Film', 'element');
	}

	public function games() {
		return $this->morphMany('App\Models\Game', 'element');
	}

}