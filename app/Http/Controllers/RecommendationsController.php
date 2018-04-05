<?php namespace App\Http\Controllers;

use App\Models\Helpers\SectionsHelper;
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

		$input = $request->all();

    	if(count($input)) {

    		//echo DebugHelper::dump($input);

    		$user_id = Auth::user()->id;

    		$type = SectionsHelper::getSectionType($input['element_type']);

    		$exploded_rate = explode(' - ', $input['rates']);

    		$min_rate = $exploded_rate[0];
    		$max_rate = $exploded_rate[1];

			if('liked_genres' == $input['recommendation_principle']) {

				$options = array(
					'total_rates' => ($input['recommendations'] * 10),
					'min_rate' => $min_rate,
					'max_rate' => $max_rate,
					'total_gens' => $input['recommendations'],
				);

				$genres = UserHelper::getFavGenres($user_id, $type, $options);

				echo DebugHelper::dump($genres);

			}

		}

		$forms = array();

		$minutes = 60 * 24;

		$forms['largest_publishers'] = Cache::remember('largest_publishers4', $minutes, function () {

			$largest_publishers_query = 'select companies.id as company_id, companies.name as company_name, count(publishers_books.id) as published_books
				from publishers_books left join companies on company_id = companies.id
				group by companies.id, companies.name
				order by published_books DESC
				limit 10
			';

			return DB::select($largest_publishers_query);

		});

		$forms['cinema_countries'] = Cache::remember('cinema_countries4', $minutes, function () {

			$cinema_countries_query = 'select countries.id as country_id, countries.name as country_name, count(countries_films.id) as shot_films
				from countries_films left join countries on country_id = countries.id
				group by countries.id, countries.name
				order by shot_films DESC
				limit 20'
			;

			return DB::select($cinema_countries_query);

		});

		$forms['top_platforms'] = Cache::remember('top_platforms4', $minutes, function () {

			$top_platforms_query = 'select platforms.id as platform_id, platforms.name as platform_name, count(platforms_games.id) as developed_games
				from platforms_games left join platforms on platform_id = platforms.id
				group by platforms.id, platforms.name
				order by developed_games DESC
				limit 20'
			;

			return DB::select($top_platforms_query);

		});

    	$elements = new Collection();

		return View::make('recommendations.personal', array(
			'request' => $request,
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