<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotWanted extends Model  {

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
	 * Отношение с пользователями
	 */
	public function user() {

		return $this->belongsTo('User');

	}

	public function element() {

		return $this->morphTo();

	}

}
