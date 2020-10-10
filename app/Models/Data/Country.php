<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Eloquent;

class Country extends Eloquent {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'countries';

	protected $fillable = array('name');
	
	public $timestamps = false;

	protected $visible = ['id', 'name'];

	public bool $verification = false;
    public bool $has_description = true;

	/**
	 * Отношение с
	 */
	public function films() {

		return $this->belongsToMany('App\Models\Data\Film', 'countries_films', 'country_id', 'film_id');

	}

}
