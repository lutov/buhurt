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
	 * @param string $section
	 * @return Section
	 */
	public static function getSection(string $section) {

		$minutes = 60;
		$var_name = $section.'_model';
		$result = Cache::remember($var_name, $minutes, function() use ($section) {

			return Section::where('alt_name', '=', $section)->first();

		});

		return $result;

	}

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
	 * @param $type
	 * @return mixed
	 */
	public static function getSectionBy($type) {

		$minutes = 60;
		$var_name = $type.'_section';
		$result = Cache::remember($var_name, $minutes, function() use ($type) {

			return Section::where('type', '=', $type)->value('alt_name');

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