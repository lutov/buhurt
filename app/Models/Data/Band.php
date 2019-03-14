<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Eloquent;

/**
 * @property string name
 */
class Band extends Eloquent {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bands';

	protected $morphClass = 'Band';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'bio');

	public function albums() {

		return $this->belongsToMany('App\Models\Data\Album', 'bands_albums', 'band_id', 'album_id');

	}

	/**
	 * Отношение с книгами
	 */
	public function members() {

		return $this->belongsToMany('App\Models\Data\Person', 'bands_persons', 'band_id', 'person_id');

	}

}
