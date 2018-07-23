<?php namespace App\Http\Controllers;

use App\Models\Helpers\SectionsHelper;
use App\Models\Rate;
use App\Models\Wanted;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\Models\Helpers;
use App\Models\Poster;

use App\Models\Helpers\DebugHelper;
use App\Models\Helpers\UserHelper;
use NotWanted;

class RecommendationsController extends Controller {

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
    public function get(Request $request) {

		/**
		 * Array (
			[element_type] => films
			[ratings] => any
			[rates_count] => any
			[years] => 2000 - 2018
			[include_wanted] => 1
			[include_not_wanted] => 1
			[recommendation_principle] => liked_genres
			[rates] => 7 - 10
			[checkbox-31] => on
			[checkbox-21] => on
			[checkbox-1] => on
			[recommendations] => 15
		 * )
		 */

		$section = '';
		$elements = new Collection();

		$input = $request->all();

		$minutes = 60 * 24;

    	if(count($input)) {

    		//echo DebugHelper::dump($input);

			/*
Array
(
    [element_type] => films
    [ratings] => any
    [rates_count] => any
    [years] => 2000;2018
    [recommendation_principle] => liked_genres
    [rates] => 7;10
    [country] => Array
        (
            [0] => 20
            [1] => 18
            [2] => 19
            [3] => 5
            [4] => 23
            [5] => 6
            [6] => 27
            [7] => 12
            [8] => 11
            [9] => 10
            [10] => 1
            [11] => 13
            [12] => 3
            [13] => 25
            [14] => 22
            [15] => 26
            [16] => 7
            [17] => 17
            [18] => 15
            [19] => 24
        )

    [recommendations] => 15
)
			 */

    		$user_id = Auth::user()->id;

    		$section = $input['element_type'];
    		$type = SectionsHelper::getSectionType($section);
    		$object = SectionsHelper::getObjectBy($section);

    		$exploded_rate = explode(';', $input['rates']);
    		$min_rate = $exploded_rate[0];
    		$max_rate = $exploded_rate[1];

    		$exploded_year = explode(';', $input['years']);
    		$min_year = $exploded_year[0];
    		$max_year = $exploded_year[1];

			$principle = array();

			$limit = $input['recommendations'];

			$exclude = array();

			$var_name = 'rated_'.$type.'_'.Auth::user()->id;
			$rated = Cache::remember($var_name, $minutes, function () use ($type) {

				$rated = Rate::where('user_id', '=', Auth::user()->id)
					->where('element_type', '=', $type)
					->pluck('element_id')
					->toArray()
				;

				return $rated;

			});
			$exclude = array_merge($exclude, $rated);

			if(isset($input['include_wanted'])) {
				if (1 !== $input['include_wanted']) {

					$wanted = Wanted::where('user_id', '=', Auth::user()->id)
						->where('element_type', '=', $type)
						->pluck('element_id')
						->toArray()
					;
					$exclude = array_merge($exclude, $wanted);

				}
			}

			if(isset($input['include_not_wanted'])) {
				if (1 !== $input['include_not_wanted']) {

					$not_wanted = NotWanted::where('user_id', '=', Auth::user()->id)
						->where('element_type', '=', $type)
						->pluck('element_id')
						->toArray()
					;
					$exclude = array_merge($exclude, $not_wanted);

				}
			}

			$genres = array();
			if('liked_genres' == $input['recommendation_principle']) {

				$options = array(
					'total_rates' => ($input['recommendations'] * 10),
					'min_rate' => $min_rate,
					'max_rate' => $max_rate,
					'total_gens' => $input['recommendations'],
				);

				$genres = UserHelper::getFavGenres($user_id, $type, $options);

				//echo DebugHelper::dump($genres);

			} elseif('faved_genres' == $input['recommendation_principle']) {

				$options = array(
					//'total_rates' => ($input['recommendations'] * 10),
					//'min_rate' => $min_rate,
					//'max_rate' => $max_rate,
					'total_gens' => $input['recommendations'],
				);

				$genres = UserHelper::getTopGenres($user_id, $type, $options);

				//echo DebugHelper::dump($genres);

			} elseif('more_of_the_same' == $input['recommendation_principle']) {



			} elseif('similar_users' == $input['recommendation_principle']) {



			}

			$elements = $object->select($section.'.*')
				/*
				->with(array('rates' => function($query) use($type)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', $type)
						;
					})
				)
				*/
				->leftJoin('elements_genres', $section.'.id', '=', 'elements_genres.element_id')
				->where('element_type', '=', $type)
				->whereIn('elements_genres.genre_id', $genres)
				->whereBetween('year', array($min_year, $max_year))
				->whereNotIn($section.'.id', $exclude)
				//->whereIn('id', $principle)
				->inRandomOrder()
				->limit($limit)
				//->toSql()
				->get()
			;
			//die($elements);

		}

		$forms = array();

		$forms['largest_publishers'] = Cache::remember('largest_publishers', $minutes, function () {

			$largest_publishers_query = 'select companies.id as company_id, companies.name as company_name, count(publishers_books.id) as published_books
				from publishers_books left join companies on company_id = companies.id
				group by companies.id, companies.name
				order by published_books DESC
				limit 10
			';

			return DB::select($largest_publishers_query);

		});

		$forms['cinema_countries'] = Cache::remember('cinema_countries', $minutes, function () {

			$cinema_countries_query = 'select countries.id as country_id, countries.name as country_name, count(countries_films.id) as shot_films
				from countries_films left join countries on country_id = countries.id
				group by countries.id, countries.name
				order by shot_films DESC
				limit 20'
			;

			return DB::select($cinema_countries_query);

		});

		$forms['top_platforms'] = Cache::remember('top_platforms', $minutes, function () {

			$top_platforms_query = 'select platforms.id as platform_id, platforms.name as platform_name, count(platforms_games.id) as developed_games
				from platforms_games left join platforms on platform_id = platforms.id
				group by platforms.id, platforms.name
				order by developed_games DESC
				limit 20'
			;

			return DB::select($top_platforms_query);

		});

		return View::make('recommendations.personal', array(
			'request' => $request,
			'section' => $section,
			'elements' => $elements,
			'forms' => $forms,
		));
    }

	/**
	 * @return mixed
	 */
    public function gag() {

    	$elements = new Collection();

		return View::make('recommendations.gag', array(
			'elements' => $elements,
		));
    }
	
}