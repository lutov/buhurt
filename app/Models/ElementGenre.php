<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElementGenre extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'elements_genres';

	/**
	 * Отношение с
	 */
	public function genre()
	{
		return $this->belongsTo('App\Models\Genre');
	}


	/**
	 * Отношение с
	 */
	public function books()
	{
		return $this->belongsToMany('App\Models\Book', 'elements_genres', 'genre_id', 'element_id');
	}

}
