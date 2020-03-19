<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Eloquent;

/**
 * @property string name
 * @property string description
 */
class Company extends Eloquent  {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'companies';

	protected $morphClass = 'Company';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name','alt_name', 'description');

	public bool $verification = false;

	/**
	 * @return mixed
	 */
	public function books_published() {

		return $this->belongsToMany('App\Models\Data\Book', 'publishers_books', 'company_id', 'book_id');

	}

	/**
	 * @return mixed
	 */
	public function games_developed() {

		return $this->belongsToMany('App\Models\Data\Game', 'developers_games', 'company_id', 'game_id');

	}

	/**
	 * @return mixed
	 */
	public function games_published() {

		return $this->belongsToMany('App\Models\Data\Game', 'publishers_games', 'company_id', 'game_id');

	}


}
