<?php namespace App\Models;

use Eloquent;

class ElementRelation extends Eloquent {

	protected $table = 'elements_relations';

	public $timestamps = false;
	
	public function books() {
		return $this->hasMany('App\Models\Book', 'id', 'element_id');
	}
	public function films() {
		return $this->hasMany('App\Models\Film', 'id', 'element_id');
	}
	public function games() {
		return $this->hasMany('App\Models\Game', 'id', 'element_id');
	}
	public function albums() {
		return $this->hasMany('App\Models\Album', 'id', 'element_id');
	}
	public function memes() {
		return $this->hasMany('App\Models\Meme', 'id', 'element_id');
	}

	public function relation() {
		return $this->belongsTo('App\Models\Relation');
	}

}