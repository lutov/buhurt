<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Helpers;

class DatatypeHelper {

	/**
	 * @param $object
	 * @param string $delimiter
	 * @param string $path
	 * @param bool $no_link
	 * @return string $string
	 */
	public static function arrayToString($object, $delimiter = ', ', $path = '/', $no_link = false, $itemprop = false) {

		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property)
		{
			if (!$no_link) {
				$string .= '<a href="'.$path.$property->id.'"';
				if($itemprop) {
					$string .= ' itemprop="'.$itemprop.'" ';
				}
				$string .= '>';
			}
			$string .= $property->name;
			if (!$no_link) {$string .= '</a>';}
			if ($i < $number)
			{
				$string .= $delimiter;
			}
			$i++;
		}
		return $string;

	}

	/**
	 * @param $object
	 * @param string $type
	 * @param string $delimiter
	 * @param string $path
	 * @param bool $no_link
	 * @param bool $itemprop
	 * @return string
	 */
	public static function collectionToString($object, $type = 'collection', $delimiter = ', ', $path = '/', $no_link = false, $itemprop = false) {

		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property) {
			
			if(is_object($property) && is_object($property->$type)) {
			
				//echo DebugHelper::dump($property->$type->id, 1); die();
				
				if (!$no_link) {
					
					$string .= '<a href="'.$path.$property->$type->id.'"';
					if($itemprop) {
						$string .= ' itemprop="'.$itemprop.'" ';
					}
					$string .= '>';
				}
				$string .= $property->$type->name;
				if (!$no_link) {$string .= '</a>';}
				if ($i < $number)
				{
					$string .= $delimiter;
				}
				$i++;
			
			}
			
		}
		return $string;

	}

	/**
	 * @param $object
	 * @param string $delimiter
	 * @param bool $no_quotes
	 * @return string
	 */
	public static function objectToJsArray($object, $delimiter = ', ', $no_quotes = false) {

		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property) {

			if (!$no_quotes) {$string .= '"';}
			$string .= $property->name;
			if (!$no_quotes) {$string .= '"';}
			if ($i < $number)
			{
				$string .= $delimiter;
			}
			$i++;

		}
		return $string;

	}

	/**
	 * @param $object
	 * @param string $id
	 * @return string
	 */
	public static function objectToList($object, $id = '') {

		$string = '<ul class="list-group list-group-flush" id="'.$id.'">';

		foreach ($object as $property) {

			$string .= '<li class="list-group-item">';
			$string .= $property->name;
			$string .= '</li>';

		}

		$string .= '</ul>';

		return $string;

	}

}