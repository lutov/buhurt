<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:36
 */

namespace App\Traits;

use App\Helpers\ElementsHelper;
use App\Models\Search\ElementRelation;

trait RelationsTrait {

	/**
	 * @return mixed
	 */
	public function relations() {
		return $this->morphToMany('App\Models\Search\Relation', 'element', 'elements_relations');
	}

	/**
	 * @return int
	 */
	public function getSimpleRelationsAttribute() {
		return ElementRelation::select(
				'elements_relations.id',
				'elements_relations.relation_id',
				'name as relation',
				'elements_relations.element_type',
				'elements_relations.element_id'
			)->join('relations', 'relation_id', 'relations.id')
			->where('to_id', '=', $this->id)
			->where('to_type', '=', $this->morphClass)
			->get()
		;
	}

}