<?php namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string element_type
 * @property int genre_id
 * @property int element_id
 */
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
	public function genre() {

		return $this->belongsTo('App\Models\Data\Genre');

	}

}
