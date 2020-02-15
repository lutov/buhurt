<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:38
 */

namespace App\Traits;

trait RatesTrait {

	/**
	 * @return mixed
	 */
	public function rates() {

		return $this->morphMany('App\Models\User\Rate', 'element');

	}

	/**
	 * @return mixed
	 */
	public function rate() {

		return $this->morphOne('App\Models\User\Rate', 'element');

	}

}