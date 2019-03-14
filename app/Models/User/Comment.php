<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	protected $softDelete = true;

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
