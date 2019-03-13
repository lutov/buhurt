<?php namespace App\Http\Controllers;

use App\Models\Helpers\ElementsHelper;
use App\Models\Helpers\SectionsHelper;
use App\Models\Helpers\TextHelper;
use Auth;
use DB;
use Illuminate\Http\Request;
use Input;
use View;
use Redirect;
use App\Models\Game;
use App\Models\Wanted;
use App\Models\ElementRelation;

class GamesController extends Controller {

	private $prefix = 'games';

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

		if(Auth::check()) {

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

			$elements = Game::where('verified', '=', 1)
				->whereNotIn('id', $not_wanted)
				->with(array('rates' => function($query)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', 'Game')
						;
					})
				)
				->orderBy($sort, $order)
				->paginate($limit)
			;
		} else {
			$elements = Game::where('verified', '=', 1)
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

			$section = SectionsHelper::getSection($this->prefix);

			$rating = ElementsHelper::countRating($game);
			
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
				$similar[] = ElementsHelper::getSimilar($sim_options);
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
		} else {
			return Redirect::to('/games/');
		}
	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getJson($id) {

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

		$rating = ElementsHelper::countRating($game);

		$section_type = 'Game';
		$relations = ElementRelation::where('to_id', '=', $id)
			->where('element_type', '=', $section_type)
			->count()
		;

		$sim_options['type'] = 'Game';
		$sim_options['genres'] = $genres;
		$sim_limit = 0;

		for($i = 0; $i < $sim_limit; $i++) {
			$similar[] = ElementsHelper::getSimilar($sim_options);
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

	/**
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function transfer(int $id = 0) {

		$section = $this->prefix;
		$type = 'Game';

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