<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:36
 */

namespace App\Traits;


trait CollectionsTrait {

	/**
	 * @return mixed
	 */
	public function collections() {

		return $this->morphMany('App\Models\Search\ElementCollection', 'element');

	}

}