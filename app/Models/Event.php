<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'events';


	//public $timestamps = false;


	public function element() {

		return $this->morphTo();

	}
	
	/**
	 * Отношение с пользователями
	 */
	public function user() {

		return $this->belongsTo('App\Models\User');
	}

}
