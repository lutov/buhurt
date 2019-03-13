<?php namespace App\Models;

use App\Models\Helpers\SectionsHelper;
use Eloquent;

class Book extends Eloquent  {

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
	 * @return string
	 */
	public function section() {

		return SectionsHelper::getSection($this->table);

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
	public function rates() {

		return $this->morphMany('App\Models\Rate', 'element');

	}

	/**
	 * @return mixed
	 */
	public function writers() {

		return $this->belongsToMany('App\Models\Person', 'writers_books', 'book_id', 'person_id');

	}

	/**
	 * @return mixed
	 */
	public function publishers() {

		return $this->belongsToMany('App\Models\Company', 'publishers_books', 'book_id', 'company_id');

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
	public function wanted() {

		return $this->morphMany('App\Models\Wanted', 'element');

	}

	/**
	 * @return mixed
	 */
	public function not_wanted() {

		return $this->morphMany('App\Models\Wanted', 'element');

	}

}
