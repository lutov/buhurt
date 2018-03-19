<?php

class Year extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'books';

	/**
	 * Отношение с фильмами
	 */
	public function films()
	{
		return $this->belongsToMany('App\Models\Film', 'films', 'person_id', 'film_id');
	}

}
