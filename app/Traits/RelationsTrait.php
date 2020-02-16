<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:36
 */

namespace App\Traits;

trait RelationsTrait {

	/**
	 * @return mixed
	 */
	public function relations() {
		return $this->morphToMany('App\Models\Search\Relation', 'element', 'elements_relations');
	}

}