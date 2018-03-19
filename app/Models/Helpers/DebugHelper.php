<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;


class DebugHelper {

	/**
	 * @param $variable
	 * @param bool $pre
	 * @return string
	 */
	public static function dump($variable, $pre = true) {

		$result = '';

		if($pre) {$result .= '<pre>';}

		$result .= print_r($variable, true);

		if($pre) {$result .= '</pre>';}

		return $result;

	}

}