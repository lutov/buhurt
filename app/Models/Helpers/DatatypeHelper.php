<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;


class DatatypeHelper {

	/**
	 * @param $object
	 * @param string $delimiter
	 * @param string $path
	 * @param bool $no_link
	 * @return string $string
	 */
	public static function array2string($object, $delimiter = ', ', $path = '/', $no_link = false, $itemprop = false) {
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
	 * @return string $string
	 */
	public static function collection2string($object, $type = 'collection', $delimiter = ', ', $path = '/', $no_link = false, $itemprop = false) {
		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property)
		{
			if (!$no_link) {$string .= '<a href="'.$path.$property->$type->id.'"';
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
		return $string;
	}

	/**
	 * @param $object
	 * @param string $delimiter
	 * @param bool $no_quotes
	 * @return string
	 */
	public static function object2js_array($object, $delimiter = ', ', $no_quotes = false)
	{
		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property)
		{
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
	public static function object2list($object, $id = '')
	{
		$string = '<ul id="'.$id.'">';

		foreach ($object as $property) {

			$string .= '<li>';
			$string .= $property->name;
			$string .= '</li>';

		}

		$string .= '</ul>';

		return $string;
	}

}