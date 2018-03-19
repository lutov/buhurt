<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use DB;
use Cache;
use App\Models\Rate;
use App\Models\Genre;
use App\Models\ElementGenre;

class UserHelper {

	/**
	 * @param int $user_id
	 * @param string $type
	 * @param array $options
	 * @param bool $debug
	 * @return \Illuminate\Support\Collection
	 */
	public static function getFavGenres(int $user_id = 0, string $type = '', array $options = array(), bool $debug = false) {

		$fav_elems = Rate::where('user_id', '=', $user_id)
			->whereBetween('rate', [$options['min_rate'], $options['max_rate']])
			->where('element_type', '=', $type)
			->limit($options['total_rates'])
			//->toSql()
			->pluck('element_id')
		;

		if($debug) {echo DebugHelper::dump($fav_elems);}

		$fav_genres = ElementGenre::select(DB::raw('genre_id, count(`element_id`) as el_count'))
			->where('element_type', '=', $type)
			->whereIn('element_id', $fav_elems)
			->groupBy('genre_id')
			->orderBy('el_count', 'desc')
			->limit($options['total_gens'])
			->pluck('genre_id')
		;

		return $fav_genres;

	}

	/**
	 * @param int $user_id
	 * @param string $type
	 * @return mixed
	 */
	public static function getFavGenresNames(int $user_id = 0, string $type = '') {

		$options['total_rates'] = 100;
		$options['min_rate'] = 7;
		$options['max_rate'] = 10;
		$options['total_gens'] = 3;

		$options['minutes'] = 60*24;
		$options['var_name'] = 'fav_gens_'.$type.'_user_'.$user_id;

		$value = Cache::remember($options['var_name'], $options['minutes'], function() use ($user_id, $type, $options) {

			$fav_genres = UserHelper::getFavGenres($user_id, $type, $options);

			$fav_gen_names = Genre::where('element_type', '=', $type)
				->whereIn('id', $fav_genres)
				//->pluck('name')
				->get()
			;

			return $fav_gen_names;

		});

		return $value;

		//'<pre>'.print_r($fav_gen_names, true).'</pre>';

	}

}