<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sections';

	public function element() {

		return $this->morphTo();

	}

}
