<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 14.03.2019
 * Time: 13:24
 */

namespace App\Traits;

use App\Helpers\SectionsHelper;
use App\Models\Data\Section;

trait SectionTrait {

	private $parent = false;

	/**
	 * @return \App\Models\Data\Section
	 */
	public function section() {

		return SectionsHelper::getSection($this->table);

	}

	/**
	 * @param Section $section
	 */
	public function setParent(Section $section) {

		if($section) {$this->parent = $section;}

	}

	/**
	 * @return bool
	 */
	public function parent() {

		return $this->parent;

	}

}