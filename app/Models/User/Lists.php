<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 02.09.2018
 * Time: 12:23
 */

namespace App\Models\User;

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
	 * @return mixed
	 */
	public function user() {

		return $this->belongsTo('App\Models\User\User');

	}

	/**
	 * @return mixed
	 */
	public function element() {

		return $this->morphTo();

	}

}