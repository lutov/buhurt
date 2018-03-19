<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use App\Models\Section;

class SectionsHelper {

	/**
	 * @param $section
	 * @return mixed
	 */
	public static function get_section_name($section) {

		$result = Section::where('alt_name', '=', $section)->value('name'); //->remember(60)
		return $result;

	}


	/**
	 * @param $section
	 * @return mixed
	 */
	public static function get_section_type($section) {

		$result = Section::where('alt_name', '=', $section)->value('type'); //->remember(60)
		return $result;

	}


	/**
	 * @param $section
	 * @return mixed
	 */
	public static function get_object_by($section) {

		$type = SectionsHelper::get_section_type($section);
		$result = new $type;

		return $result;

	}

}