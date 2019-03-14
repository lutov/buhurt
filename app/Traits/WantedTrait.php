<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 14:18
 */

namespace App\Traits;


trait WantedTrait {

	/**
	 * @return mixed
	 */
	public function wanted() {

		return $this->morphMany('App\Models\User\Wanted', 'element');

	}

}