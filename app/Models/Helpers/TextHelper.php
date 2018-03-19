<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;


class TextHelper {

	/**
	 * @param $input_text - исходная строка
	 * @param int $limit - количество слов по умолчанию
	 * @param string $end_str - символ/строка завершения. Вставляется в конце обрезанной строки
	 * @return string
	 */
	public static function words_limit($input_text, $limit = 50, $end_str = '…') {

		$input_text = strip_tags($input_text);
		$words = explode(' ', $input_text); // создаём из строки массив слов
		if ($limit < 1 || sizeof($words) <= $limit) { // если лимит указан не верно или количество слов меньше лимита, то возвращаем исходную строку
			return $input_text;
		}
		$words = array_slice($words, 0, $limit); // укорачиваем массив до нужной длины
		$out = implode(' ', $words);
		return $out.$end_str; //возвращаем строку + символ/строка завершения

	}

	/**
	 * @param $str
	 * @return string
	 */
	public static function mb_ucwords($str) {

		$str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
		return ($str);

	}

	/**
	 * @param $word
	 * @param bool $all2lower
	 * @return string
	 */
	public static function mb_ucfirst ($word, $all2lower = false) {

		if($all2lower) {
			return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word), 'UTF-8');
		} else {
			return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, mb_strlen($word), 'UTF-8');
		}

	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function switch2en($string) {

		$search = array(
			"й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
			"ф","ы","в","а","п","р","о","л","д","ж","э",
			"я","ч","с","м","и","т","ь","б","ю"
		);
		$replace = array(
			"q","w","e","r","t","y","u","i","o","p","[","]",
			"a","s","d","f","g","h","j","k","l",";","'",
			"z","x","c","v","b","n","m",",","."
		);
		//die(str_replace($search, $replace, $string));
		return str_replace($search, $replace, $string);

		//return $string;
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public static function switch2ru($string) {

		$search = array(
			"q","w","e","r","t","y","u","i","o","p","[","]",
			"a","s","d","f","g","h","j","k","l",";","'",
			"z","x","c","v","b","n","m",",","."
		);
		$replace = array(
			"й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
			"ф","ы","в","а","п","р","о","л","д","ж","э",
			"я","ч","с","м","и","т","ь","б","ю"
		);
		//die(str_replace($search, $replace, $string));
		return str_replace($search, $replace, $string);

		//return $string;
	}

	/**
	 * @param $n
	 * @param $titles
	 * @return mixed
	 */
	public static function number($n, $titles) {

		$cases = array(2, 0, 1, 1, 1, 2);
		return $n.' '.$titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];

	}

}