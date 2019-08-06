<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Helpers\DummyHelper;
use App\Helpers\SectionsHelper;
use App\Models\Data\Book;
use App\Models\Search\ElementRelation;
use App\Models\User\Rate;
use App\Models\User\Unwanted;
use App\Models\User\Wanted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Helpers\UserHelper;

class RecommendationsController extends Controller {

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
    public function get(Request $request) {

		$elements = new Collection();

		$input = $request->all();

		$section = DummyHelper::inputOrSession($request, 'element_type', 'section', 'films');

		$principle = DummyHelper::inputOrSession($request, 'recommendation_principle', 'principle', 'liked_genres');

		$rates = DummyHelper::inputOrSession($request, 'rates', 'rates', '7;10');
		$exploded_rate = explode(';', $rates);
		$min_rate = $exploded_rate[0];
		$max_rate = $exploded_rate[1];

		$years = DummyHelper::inputOrSession($request, 'years', 'years', '2000;'.date('Y'));
		$exploded_year = explode(';', $years);
		$min_year = $exploded_year[0];
		$max_year = $exploded_year[1];

		$limit = DummyHelper::inputOrSession($request, 'recommendations', 'limit', '15');

		$include_wanted = DummyHelper::inputOrSession($request, 'include_wanted', 'include_wanted', 0);
		$include_unwanted = DummyHelper::inputOrSession($request, 'include_unwanted', 'include_unwanted', 0);

		$minutes = 60 * 24;

    	if(count($input)) {

    		$user_id = Auth::user()->id;

    		$type = SectionsHelper::getSectionType($section);
    		$object = SectionsHelper::getObjectBy($section);

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

			if (1 !== $include_wanted) {

				$wanted = Wanted::where('user_id', '=', Auth::user()->id)
					->where('element_type', '=', $type)
					->pluck('element_id')
					->toArray()
				;
				$exclude = array_merge($exclude, $wanted);

			}

			if (1 !== $include_unwanted) {

				$unwanted = Unwanted::where('user_id', '=', Auth::user()->id)
					->where('element_type', '=', $type)
					->pluck('element_id')
					->toArray()
				;
				$exclude = array_merge($exclude, $unwanted);

			}

			$genres = array();
			if('liked_genres' == $principle) {

				$options = array(
					'total_rates' => ($limit * 10),
					'min_rate' => $min_rate,
					'max_rate' => $max_rate,
					'total_gens' => $limit,
				);

				$genres = UserHelper::getFavGenres($user_id, $type, $options);

				//echo DebugHelper::dump($genres);

			} elseif('faved_genres' == $principle) {

				$options = array(
					//'total_rates' => ($input['recommendations'] * 10),
					//'min_rate' => $min_rate,
					//'max_rate' => $max_rate,
					'total_gens' => $limit,
				);

				$genres = UserHelper::getTopGenres($user_id, $type, $options);

				//echo DebugHelper::dump($genres);

			} elseif('more_of_the_same' == $principle) {



			} elseif('similar_users' == $principle) {



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
			'principle' => $principle,
			'options' => array(
				'rates' => array(
					'from' => $min_rate,
					'to' => $max_rate,
				),
				'years' => array(
					'from' => $min_year,
					'to' => $max_year,
					'max' => date('Y'),
				),
				'limit' => $limit,
				'include_wanted' => $include_wanted,
				'include_unwanted' => $include_unwanted,
			),
			'elements' => $elements,
			'forms' => $forms,
		));
    }

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
    public function gag(Request $request) {

		$input = $request->all();

    	$elements = new Collection();

		$section = DummyHelper::inputOrSession($request, 'element_type', 'section', 'films');

		$years = DummyHelper::inputOrSession($request, 'years', 'years', '2000;'.date('Y'));
		$exploded_year = explode(';', $years);
		$min_year = $exploded_year[0];
		$max_year = $exploded_year[1];

		$limit = DummyHelper::inputOrSession($request, 'recommendations', 'limit', '15');

		if(count($input)) {

			$object = SectionsHelper::getObjectBy($section);

			$elements = $object->select($section . '.*')
				->whereBetween('year', array($min_year, $max_year))
				->inRandomOrder()
				->limit($limit)
				//->toSql()
				->get();

		}

		return View::make('recommendations.gag', array(
			'request' => $request,
			'section' => $section,
			'options' => array(
				'years' => array(
					'from' => $min_year,
					'to' => $max_year,
					'max' => date('Y'),
				),
				'limit' => $limit,
			),
			'elements' => $elements,
		));
    }

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function advise(Request $request) {

    	if(!Auth::check()) {return Redirect::to()->with('message', 'Чтобы получить совет, нужно авторизоваться');}

    	$user = Auth::user();

    	$random_wanted_book = Wanted::where('user_id', '=', $user->id)
			->where('element_type', '=', 'Book')
			->inRandomOrder()
			->first()
		;

    	if(!empty($random_wanted_book)) {

			$book = Book::find($random_wanted_book->element_id);

			$similar = array();

			$writers = $book->writers;
			$publishers = $book->publishers;
			$genres = $book->genres; $genres = $genres->sortBy('name');
			$collections = $book->collections;

			$user_rate = 0;

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/books/' . $book->id . '.jpg';
			if (file_exists($file_path)) {
				$cover = $book->id;
			}

			$section_type = 'Book';
			$relations = ElementRelation::where('to_id', '=', $book->id)
				->where('to_type', '=', $section_type)
				->count()
			;

			$book->options = array(
				'rate' => $user_rate,
				'genres' => $genres,
				'cover' => $cover,
				'similar' => collect($similar),
				'collections' => $collections,
				'relations' => $relations,
				'writers' => $writers,
				'publishers' => $publishers,
			);

		} else {
    		$book = new Book;
		}

    	return view('recommendations.advise', array(
    		'request' => $request,
    		'user' => $user,
    		'book' => $book,
		));

	}
	
}