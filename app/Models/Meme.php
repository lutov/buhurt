<?php namespace App\Models;

use Eloquent;

class Meme extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'memes';

	protected $morphClass = 'Meme';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'alt_name', 'description', 'year', 'verified');

	/**
	 * Отношение с жанрами
	 */
	public function genres() {

		return $this->morphMany('App\Models\ElementGenre', 'element');

	}

	/**
	 * Отношение с коллекциями
	 */
	public function collections() {

		return $this->morphMany('App\Models\ElementCollection', 'element');

	}


	/**
	 * Отношение с оценками
	 */
	public function rates() {

		return $this->morphMany('App\Models\Rate', 'element');

	}

	/**
	 * Отношение с комментариями
	 */
	public function comments() {

		return $this->morphMany('App\Models\Comment', 'element');

	}

	/**
	 * Отношение с желаемым
	 */
	public function wanted() {

		return $this->morphMany('App\Models\Wanted', 'element');

	}


	/**
	 * Отношение с нежелаемым
	 */
	public function not_wanted() {

		return $this->morphMany('App\Models\NotWanted', 'element');

	}

}
