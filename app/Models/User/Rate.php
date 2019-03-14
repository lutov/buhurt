<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rates';

	//public $timestamps = false;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function element() {

		return $this->morphTo();

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {

		return $this->belongsTo('App\Models\User\User');

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function book() {

		return $this->morphMany('App\Models\Data\Book', 'section');

	}

}
