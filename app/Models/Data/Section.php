<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

	public bool $verification = false;

	public function element() {

		return $this->morphTo();

	}

	/**
	 * @return BelongsTo
	 */
	public function parent() {

		return $this->belongsTo('App\Models\Data\Section', 'parent_id', 'id');

	}

}
