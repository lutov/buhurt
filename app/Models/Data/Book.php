<?php namespace App\Models\Data;

use App\Traits\CollectionsTrait;
use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\IsWantedTrait;
use App\Traits\RatesTrait;
use App\Traits\SectionTrait;
use App\Traits\WantedTrait;
use Eloquent;

/**
 * @property int id
 */
class Book extends Eloquent  {

	use SectionTrait;
	use GenresTrait;
	use CollectionsTrait;
	use RatesTrait;
	use CommentsTrait;
	use WantedTrait;
	use IsWantedTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'books';

	protected $morphClass = 'Book';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'alt_name', 'description', 'year', 'verified');

	/**
	 * @return mixed
	 */
	public function writers() {

		return $this->belongsToMany('App\Models\Data\Person', 'writers_books', 'book_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function publishers() {

		return $this->belongsToMany('App\Models\Data\Company', 'publishers_books', 'book_id', 'company_id');

	}

}
