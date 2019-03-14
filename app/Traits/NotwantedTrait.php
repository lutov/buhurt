<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 14:55
 */

namespace App\Traits;


trait NotwantedTrait {

	/**
	 * @return mixed
	 */
	public function not_wanted() {

		return $this->morphMany('App\Models\User\NotWanted', 'element');

	}

}