<?php namespace App\Http\Controllers;

use App\Models\Helpers\ElementsHelper;
use Auth;
use DB;
use Illuminate\Http\Request;
use Input;
use View;
use Redirect;
use App\Models\Film;
use App\Models\Wanted;
use App\Models\Helpers;
use App\Models\Section;
//use App\Models\Option;
use App\Models\ElementRelation;

class FilmsController extends Controller {

	private $prefix = 'films';

	private $limit = 28;

    public function show_all(Request $request) {

		$section = $this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', $section.'.created_at');
		$sort_direction = Input::get('sort_direction', 'desc');
		$limit = 28;

		$sort_options = array(
			$section.'.created_at' => 'Время добавления',
			$section.'.name' => 'Название',
			$section.'.alt_name' => 'Оригинальное название',
			$section.'.year' => 'Год'
		);

		$wanted = array();
		$not_wanted = array();

		if(Auth::check())  {

			$user_id = Auth::user()->id;

			$wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
				->toArray()
			;

			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
				->toArray()
			;

			$elements = Film::where('verified', '=', 1)
				->whereNotIn('id', $not_wanted)
				->with(array('rates' => function($query)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', 'Film')
						;
					})
				)
				->orderBy($sort, $sort_direction)
				->paginate($limit)
			;
		} else {
			$elements = Film::where('verified', '=', 1)
				->orderBy($sort, $sort_direction)
				->paginate($limit)
			;
		}

		return View::make($this->prefix.'.index', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'ru_section' => $ru_section,
			'sort_options' => $sort_options,
			'wanted' => $wanted,
			'not_wanted' => $not_wanted,
		));
    }

    public function show_collections()
    {
        return View::make($this->prefix.'.collections');
    }

    public function show_collection()
    {
        return View::make($this->prefix.'.collection');
    }
	
    public function show_item(Request $request, $id)
	{
		$film = Film::find($id);
		if (count($film)) {
			$screenwriters = $film->screenwriters;
			$producers = $film->producers;
			$directors = $film->directors;
			$genres = $film->genres; $genres = $genres->sortBy('name')->reverse();
			$countries = $film->countries;
			$actors = $film->actors;
			$collections = $film->collections;

			//die('<pre>'.print_r($countries[0]->country, true).'</pre>');
			//die('<pre>'.print_r($producers, true).'</pre>');

			if(Auth::check()) {

				$user = Auth::user();
				$user_options = $user
					->options()
					->where('enabled', '=', 1)
					->pluck('option_id')
					->toArray();

				$is_other_private = in_array(2, $user_options);

				if($is_other_private) {

					$comments = $film->comments()
						->with('user')
						->where('user_id', '=', $user->id)
						->orderBy('created_at', 'desc')
						->get();

				} else {

					$comments = $film->comments()
						->with('user')
						->orderBy('created_at', 'desc')
						->get();

				}

			} else {

				$comments = $film->comments()
					->with('user')
					->orderBy('created_at', 'desc')
					->get()
				;

			}

			$user_rate = 0;
			$wanted = 0;
			$not_wanted = 0;
			if (Auth::check()) {
				$user_id = Auth::user()->id;
				$rate = $film->rates()->where('user_id', '=', $user_id)->first();
				if (isset($rate->rate)) {
					$user_rate = $rate->rate;
				}

				$wanted_film = $film
					->wanted()
					->where('user_id', '=', $user_id)
					->first();
				if (isset($wanted_film->id)) {
					$wanted = $wanted_film->wanted;
					$not_wanted = $wanted_film->not_wanted;
				}
			}

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/films/' . $id . '.jpg';
			if (file_exists($file_path)) {
				$cover = $id;
			}

			$section = $this->prefix;

			$rating = Helpers::count_rating($film);
			
			$section_type = 'Film';
			$relations = ElementRelation::where('to_id', '=', $id)
				->where('to_type', '=', $section_type)
				->count()
			;

			$sim_options['type'] = 'Film';
			$sim_options['genres'] = $genres;
			$sim_limit = 3;

			for($i = 0; $i < $sim_limit; $i++) {
				$similar[] = Helpers::get_similar($sim_options);
			}

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'element' => $film,
				'screenwriters' => $screenwriters,
				'producers' => $producers,
				'directors' => $directors,
				'actors' => $actors,
				'genres' => $genres,
				'collections' => $collections,
				'countries' => $countries,
				'cover' => $cover,
				'rate' => $user_rate,
				'wanted' => $wanted,
				'not_wanted' => $not_wanted,
				'comments' => $comments,
				'section' => $section,
				'rating' => $rating,
				'relations' => $relations,
				'similar' => collect($similar)
			));
		}
		else {
			return Redirect::to('/base/films');
		}
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function get_json($id) {

		$section = $this->prefix;

		$film = Film::find($id);

		$screenwriters = $film->screenwriters;
		$producers = $film->producers;
		$directors = $film->directors;
		$genres = $film->genres; $genres = $genres->sortBy('name')->reverse();
		$countries = $film->countries;
		$actors = $film->actors;
		$collections = $film->collections;

		$user_rate = 0;
		$wanted = 0;
		$not_wanted = 0;
		$cover = 0;
		$similar = array();

		$file_path = public_path() . '/data/img/covers/films/' . $id . '.jpg';
		if (file_exists($file_path)) {
			$cover = $id;
		}

		$rating = Helpers::count_rating($film);

		$section_type = 'Film';
		$relations = ElementRelation::where('to_id', '=', $id)
			->where('to_type', '=', $section_type)
			->count()
		;

		$sim_options['type'] = 'Film';
		$sim_options['genres'] = $genres;
		$sim_limit = 0;

		for($i = 0; $i < $sim_limit; $i++) {
			$similar[] = Helpers::get_similar($sim_options);
		}

		return View::make($this->prefix . '.json', array(
			'film' => $film,
			'screenwriters' => $screenwriters,
			'producers' => $producers,
			'directors' => $directors,
			'actors' => $actors,
			'genres' => $genres,
			'collections' => $collections,
			'countries' => $countries,
			'cover' => $cover,
			'rate' => $user_rate,
			'wanted' => $wanted,
			'not_wanted' => $not_wanted,
			'section' => $section,
			'rating' => $rating,
			'relations' => $relations,
			'similar' => collect($similar)
		));

	}

	/**
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function transfer(int $id = 0) {

		$section = $this->prefix;
		$type = 'Film';

		$recipient_id = Input::get('recipient_id');

		$element_rate = DB::table('rates')
			->where('element_type', '=', $type)
			->where('element_id', '=', $recipient_id)
			->get()
			->toArray()
		;

		//echo Helpers\DebugHelper::dump($element_rate); die();

		if(!isset($element_rate[0]->id)) {

			DB::table('rates')
				->where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->update(array('element_id' => $recipient_id))
			;

		}

		ElementsHelper::deleteElement($id, $section, $type);

		return Redirect::to('/'.$this->prefix.'/'.$recipient_id);

	}
	
}