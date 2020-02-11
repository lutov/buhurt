<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Helpers;

use App\Models\Data\Section;
use Illuminate\Support\Facades\Cache;

class SectionsHelper {

	/**
	 * @param string $section
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getSection(string $section, bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = $section . '_model';
			$result = Cache::remember($var_name, $minutes, function () use ($section) {
				return Section::where('alt_name', '=', $section)->first();
			});
		} else {
			$result = Section::where('alt_name', '=', $section)->first();
		}
		return $result;
	}

	/**
	 * @param string $section
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getSectionName(string $section, bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = $section . '_name';
			$result = Cache::remember($var_name, $minutes, function () use ($section) {
				return Section::where('alt_name', '=', $section)->value('name');
			});
		} else {
			$result = Section::where('alt_name', '=', $section)->value('name');
		}
		return $result;
	}

	/**
	 * @param string $section
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getSectionType(string $section, bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = $section . '_type';
			$result = Cache::remember($var_name, $minutes, function () use ($section) {
				return Section::where('alt_name', '=', $section)->value('type');
			});
		} else {
			$result = Section::where('alt_name', '=', $section)->value('type');
		}
		return $result;
	}

	/**
	 * @param $type
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getSectionBy($type, bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = $type . '_section';
			$result = Cache::remember($var_name, $minutes, function () use ($type) {
				return Section::where('type', '=', $type)->value('alt_name');
			});
		} else {
			$result = Section::where('type', '=', $type)->value('alt_name');
		}
		return $result;
	}

	/**
	 * @param string $section
	 * @return mixed
	 */
	public static function getObjectBy(string $section) {
		$type = self::getSectionType($section);
		return new $type;
	}

}