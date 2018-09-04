<?php
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 02.09.2018
 * Time: 12:25
 */

namespace App\Models;

use Eloquent;

class ElementList extends Eloquent {

	protected $table = 'elements_lists';

	public function relation() {
		return $this->belongsTo('App\Models\Lists');
	}

}