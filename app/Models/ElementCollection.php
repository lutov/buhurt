<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElementCollection extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'elements_collections';

	public $timestamps = false;

	/**
	 * Отношение с
	 */
	public function collection()
	{
		return $this->belongsTo('App\Models\Collection');
	}


	/**
	 * Отношение с
	 */
	public function books()
	{
		return $this->belongsToMany('App\Models\Book', 'elements_collection', 'collection_id', 'element_id');
	}

}
