<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed alt_name
 * @property mixed type
 */
class Section extends Model {

	use SectionTrait;

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
