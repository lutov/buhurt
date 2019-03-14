<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:24
 */

namespace App\Traits;

use App\Helpers\SectionsHelper;

trait SectionTrait {

	/**
	 * @return \App\Models\Data\Section
	 */
	public function section() {

		return SectionsHelper::getSection($this->table);

	}

}