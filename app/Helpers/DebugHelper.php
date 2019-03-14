<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Helpers;

class DebugHelper {

	/**
	 * @param $variable
	 * @param bool $pre
	 * @return string
	 */
	public static function dump($variable, $pre = true) {

		$result = '';

		if($pre) {$result .= '<pre class="mt-5">';}

		$result .= print_r($variable, true);

		if($pre) {$result .= '</pre>';}

		return $result;

	}

	/**
	 * @param string $debug_text
	 * @param string $file_name
	 * @return string
	 */
	public static function dumpToFile(string $debug_text = '', string $file_name = 'debug') {

		$file_path = env('LOG_PATH');

		$log_path = $file_path.'/'.$file_name.date(' Y-m-d H-i-s').'.log';

		file_put_contents($log_path, $debug_text);

		return $log_path;

	}

	/**
	 * @param string $url
	 * @param bool $debug
	 * @return array|mixed
	 */
	public static function getResult(string $url = '', bool $debug = false) { // , $proxy = false

		$result = array();

		if(!empty($url)) {

			$json_result = file_get_contents($url); // , false, $proxy
			if($debug) {
				$result['debug']['url'] = $url;
				$result['debug']['result'] = $json_result;
			} else {
				$result = json_decode($json_result);
			}

		}

		return $result;

	}

	/**
	 * @param string $url
	 * @param array $params
	 * @param bool $debug
	 * @return array|mixed
	 */
	public static function makeRequest($url = '', $params = array(), $debug = false) {

		$result = array();

		if(!empty($url)) {

			$query = http_build_query($params);
			if($debug) {$result['debug']['query'] = $query;}

			$path = $url.'?'.$query;
			if($debug) {$result['debug']['path'] = $path;}

			$query_result = DebugHelper::getResult($path, $debug);
			if(!$debug) {$result = $query_result;}

		}

		return $result;

	}

	/**
	 * @param string $url
	 * @param array $data
	 * @param bool $debug
	 * @return mixed
	 */
	public static function makePostRequest(string $url = '', array $data = array(), bool $debug = false) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$output = curl_exec($ch);
		curl_close($ch);

		$result = json_decode($output, JSON_UNESCAPED_UNICODE);

		//if($debug) {return json_encode($result);}

		return $result;

	}

}