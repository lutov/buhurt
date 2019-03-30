<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Unwanted extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'unwanted';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	protected $softDelete = true;

	/**
	 * Отношение с пользователями
	 */
	public function user() {

		return $this->belongsTo('App\Models\User\User');

	}

	public function element() {

		return $this->morphTo();

	}

}
