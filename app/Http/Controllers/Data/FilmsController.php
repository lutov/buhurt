<?php namespace App\Http\Controllers\Data;

use App\Helpers\ElementsHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Data\Film;
use App\Models\User\Wanted;
use App\Models\Search\ElementRelation;

class FilmsController extends Controller {

	private $prefix = 'films';

    public function list(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		$sort = $request->get('sort', 'created_at');
		$order = $request->get('order', 'desc');
		$limit = 28;

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Название',
			'alt_name' => 'Оригинальное название',
			'year' => 'Год'
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$wanted = array();
		$not_wanted = array();

		if(Auth::check())  {

			$user_id = Auth::user()->id;

			$wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
				->toArray()
			;

			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
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
				->orderBy($sort, $order)
				->paginate($limit)
			;
		} else {
			$elements = Film::where('verified', '=', 1)
				->orderBy($sort, $order)
				->paginate($limit)
			;
		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'wanted' => $wanted,
			'not_wanted' => $not_wanted,
			'sort_list' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make($this->prefix.'.index', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'options' => $options,
		));
    }

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
    public function item(Request $request, $id) {

		$film = Film::find($id);
		if (count($film)) {

			$screenwriters = $film->screenwriters;
			$producers = $film->producers;
			$directors = $film->directors;
			$genres = $film->genres; $genres = $genres->sortBy('name')->reverse();
			$countries = $film->countries;
			$actors = $film->actors;
			$collections = $film->collections;
			$similar = array();

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

			$section = SectionsHelper::getSection($this->prefix);

			$rating = ElementsHelper::countRating($film);
			
			$section_type = 'Film';
			$relations = ElementRelation::where('to_id', '=', $id)
				->where('to_type', '=', $section_type)
				->count()
			;

			$sim_options['type'] = 'Film';
			$sim_options['genres'] = $genres;
			$sim_limit = 3;

			for($i = 0; $i < $sim_limit; $i++) {
				$similar[] = ElementsHelper::getSimilar($sim_options);
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

		} else {
			return Redirect::to('/base/films');
		}
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getJson($id) {

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

		$rating = ElementsHelper::countRating($film);

		$section_type = 'Film';
		$relations = ElementRelation::where('to_id', '=', $id)
			->where('to_type', '=', $section_type)
			->count()
		;

		$sim_options['type'] = 'Film';
		$sim_options['genres'] = $genres;
		$sim_limit = 0;

		for($i = 0; $i < $sim_limit; $i++) {
			$similar[] = ElementsHelper::getSimilar($sim_options);
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
	 * @param Request $request
	 * @param int $id
	 * @return mixed
	 * @throws \Exception
	 */
	public function transfer(Request $request, int $id = 0) {

		$section = $this->prefix;
		$type = 'Film';

		$recipient_id = $request->get('recipient_id');

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