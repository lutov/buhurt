<?php namespace App\Models\Data;

use App\Traits\CollectionsTrait;
use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\RatesTrait;
use App\Traits\SectionTrait;
use App\Traits\WantedTrait;
use Eloquent;

/**
 * @property int id
 */
class Album extends Eloquent  {

	use SectionTrait;
	use GenresTrait;
	use CollectionsTrait;
	use RatesTrait;
	use CommentsTrait;
	use WantedTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'albums';

	/**
	 * @var string
	 */
	protected $morphClass = 'Album';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'description', 'year', 'verified');

	/**
	 * @return mixed
	 */
	public function bands() {

		return $this->belongsToMany('App\Models\Data\Band', 'bands_albums', 'album_id', 'band_id');

	}

	/**
	 * @return mixed
	 */
	public function tracks() {

		return $this->hasMany('App\Models\Data\Track');

	}
}
