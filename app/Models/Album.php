<?php namespace App\Models;

use App\Models\Helpers\SectionsHelper;
use Eloquent;

class Album extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'albums';

	protected $morphClass = 'Album';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'description', 'year', 'verified');

	/**
	 * @return string
	 */
	public function section() {

		return SectionsHelper::getSection($this->table);

	}

	/**
	 * @return mixed
	 */
	public function genres() {

		return $this->morphMany('App\Models\ElementGenre', 'element');

	}

	/**
	 * @return mixed
	 */
	public function collections() {

		return $this->morphMany('App\Models\ElementCollection', 'element');

	}

	/**
	 * @return mixed
	 */
	public function rates() {

		return $this->morphMany('App\Models\Rate', 'element');

	}

	/**
	 * @return mixed
	 */
	public function comments() {

		return $this->morphMany('App\Models\Comment', 'element');

	}

	/**
	 * @return mixed
	 */
	public function wanted() {

		return $this->morphMany('App\Models\Wanted', 'element');

	}

	/**
	 * @return mixed
	 */
	public function not_wanted() {

		return $this->morphMany('App\Models\NotWanted', 'element');

	}

	/**
	 * @return mixed
	 */
	public function bands() {

		return $this->belongsToMany('App\Models\Band', 'bands_albums', 'album_id', 'band_id');

	}

	/**
	 * @return mixed
	 */
	public function tracks() {

		return $this->hasMany('App\Models\Track');

	}
}
