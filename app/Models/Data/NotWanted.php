<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Illuminate\Database\Eloquent\Model;

class NotWanted extends Model  {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wanted';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	protected $softDelete = true;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {

		return $this->belongsTo('App\Models\User\User');

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function element() {

		return $this->morphTo();

	}

}
