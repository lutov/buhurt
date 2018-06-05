<?php namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use View;
use Redirect;
use App\Models\Section;
use App\Models\Helpers;
use App\Models\Game;
use App\Models\Wanted;
use App\Models\ElementRelation;

class GamesController extends Controller {

	private $prefix = 'games';

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

		if(Auth::check()) {

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

			$elements = Game::orderBy($sort, $sort_direction)
				->with(array('rates' => function($query)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', 'Game')
						;
					})
				)
				->whereNotIn('id', $not_wanted)
				->paginate($limit)
			;
		}
		else
		{
			$elements = Game::orderBy($sort, $sort_direction)
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

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
    public function show_item(Request $request, $id) {

		$game = Game::find($id);

		if(count($game)) {
			$developers = $game->developer;
			$publishers = $game->publisher;
			$platforms = $game->platforms()->orderBy('name')->get();
			$genres = $game->genres; $genres = $genres->sortBy('name')->reverse();
			$collections = $game->collections;

			//die('<pre>'.print_r($genres, true).'</pre>');
			//die('<pre>'.print_r($platforms, true).'</pre>');

			if(Auth::check()) {

				$user = Auth::user();
				$user_options = $user
					->options()
					->where('enabled', '=', 1)
					->pluck('option_id')
					->toArray();

				$is_other_private = in_array(2, $user_options);

				if($is_other_private) {

					$comments = $game->comments()
						->with('user')
						->where('user_id', '=', $user->id)
						->orderBy('created_at', 'desc')
						->get();

				} else {

					$comments = $game->comments()
						->with('user')
						->orderBy('created_at', 'desc')
						->get();

				}

			} else {

				$comments = $game->comments()
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
				$rate = $game->rates()->where('user_id', '=', $user_id)->first();
				if (isset($rate->rate)) {
					$user_rate = $rate->rate;
				}

				$wanted_game = $game
					->wanted()
					->where('user_id', '=', $user_id)
					->first();
				if (isset($wanted_game->id)) {
					$wanted = $wanted_game->wanted;
					$not_wanted = $wanted_game->not_wanted;
				}
			}

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/games/' . $id . '.jpg';
			if (file_exists($file_path)) {
				$cover = $id;
			}

			$section = $this->prefix;

			$rating = Helpers::count_rating($game);
			
			$section_type = 'Game';
			$relations = ElementRelation::where('to_id', '=', $id)
				->where('to_type', '=', $section_type)
				->count()
			;

			$sim_options['type'] = 'Game';
			$sim_options['genres'] = $genres;
			$sim_limit = 3;

			$similar = array();
			for($i = 0; $i < $sim_limit; $i++) {
				$similar[] = Helpers::get_similar($sim_options);
			}

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'element' => $game,
				'developers' => $developers,
				'publishers' => $publishers,
				'platforms' => $platforms,
				'genres' => $genres,
				'collections' => $collections,
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
			return Redirect::to('/base/games/');
		}
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function get_json($id) {

		$section = $this->prefix;

		$game = Game::find($id);

		$developers = $game->developer;
		$publishers = $game->publisher;
		$platforms = $game->platforms()->orderBy('name')->get();
		$genres = $game->genres; $genres = $genres->sortBy('name')->reverse();
		$collections = $game->collections;

		$user_rate = 0;
		$wanted = 0;
		$not_wanted = 0;
		$cover = 0;
		$similar = array();

		$file_path = public_path() . '/data/img/covers/games/' . $id . '.jpg';
		if (file_exists($file_path)) {
			$cover = $id;
		}

		$rating = Helpers::count_rating($game);

		$section_type = 'Game';
		$relations = ElementRelation::where('to_id', '=', $id)
			->where('element_type', '=', $section_type)
			->count()
		;

		$sim_options['type'] = 'Game';
		$sim_options['genres'] = $genres;
		$sim_limit = 0;

		for($i = 0; $i < $sim_limit; $i++) {
			$similar[] = Helpers::get_similar($sim_options);
		}

		return View::make($this->prefix . '.json', array(
			'game' => $game,
			'developers' => $developers,
			'publishers' => $publishers,
			'platforms' => $platforms,
			'genres' => $genres,
			'collections' => $collections,
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
	
}