<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use App\Models\Section;
use Cache;

class SectionsHelper {

	/**
	 * @param $section
	 * @return mixed
	 */
	public static function getSectionName($section) {

		$minutes = 60;
		$var_name = $section.'_name';
		$result = Cache::remember($var_name, $minutes, function() use ($section) {

			return Section::where('alt_name', '=', $section)->value('name');

		});

		return $result;

	}


	/**
	 * @param $section
	 * @return mixed
	 */
	public static function getSectionType($section) {

		$minutes = 60;
		$var_name = $section.'_type';
		$result = Cache::remember($var_name, $minutes, function() use ($section) {

			return Section::where('alt_name', '=', $section)->value('type');

		});

		return $result;

	}


	/**
	 * @param $section
	 * @return mixed
	 */
	public static function getObjectBy($section) {

		$type = SectionsHelper::getSectionType($section);
		$result = new $type;

		return $result;

	}

}