<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:43
 */

namespace App\Traits;


trait CommentsTrait {

	/**
	 * @return mixed
	 */
	public function comments() {

		return $this->morphMany('App\Models\User\Comment', 'element');

	}

}