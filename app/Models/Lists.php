<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 02.09.2018
 * Time: 12:23
 */

namespace App\Models;

use Eloquent;

class Lists extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'lists';

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