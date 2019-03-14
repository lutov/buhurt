<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Option extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'options';

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

}
