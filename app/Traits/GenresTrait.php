<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:34
 */

namespace App\Traits;

trait GenresTrait {

	/**
	 * @return mixed
	 */
	public function genres() {

		return $this->morphToMany('App\Models\Data\Genre', 'element', 'elements_genres');

	}

}