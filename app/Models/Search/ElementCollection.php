<?php namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string element_type
 * @property int collection_id
 * @property int element_id
 */
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
	public function collection() {

		return $this->belongsTo('App\Models\Data\Collection');

	}

}
