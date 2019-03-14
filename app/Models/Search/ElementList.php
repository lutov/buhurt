<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 02.09.2018
 * Time: 12:25
 */

namespace App\Models\Search;

use Eloquent;

class ElementList extends Eloquent {

	protected $table = 'elements_lists';

	/**
	 * @return mixed
	 */
	public function relation() {

		return $this->belongsTo('App\Models\User\Lists');

	}

}