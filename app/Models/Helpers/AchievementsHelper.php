<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;


class AchievementsHelper {

	/**
	 * @param $achievements
	 * @param $user_achievements
	 * @return string
	 */
	public static function render($achievements, $user_achievements) {

		$in_line = 7;
		$i = 1;

		$result = '<table class="achievements">';

		foreach($achievements as $value)
		{
			if($i == 1) {$result .= '<tr>';}

			$is_achieved = array_search($value->id, $user_achievements);

			$result .= '<td';
			if(false !== $is_achieved) {
				$path = '/data/img/achievements/'.$value->id.'.png';
				$result .=' class="achieved"';
				$content = '<img src="'.$path.'" alt="'.$value->name.'" />';
			}
			else
			{
				$content = '?';
			}
			//$result .= ' id="achievement'.$value->id.'"';
			$result .= ' title="'.$value->description.'"';
			$result .='>';
			$result .= $content;
			$result .= '</td>';

			if($i == $in_line) {$result .= '</tr>'; $i = 0;}
			$i++;
		}

		$result .= '</table>';

		return $result;

	}

}