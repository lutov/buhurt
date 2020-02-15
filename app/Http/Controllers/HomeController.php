<?php namespace App\Http\Controllers;

use App\Helpers\ElementsHelper;
use App\Helpers\SectionsHelper;
use App\Models\Data\Section;
use App\Models\User\Achievement;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller {

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index(Request $request) {

		$sort = 'updated_at';
		$order = 'desc';
		$limit = 9;

		$minutes = 10;

		$sort_options = array(
			'sort' => $sort,
			'order' => $order,
			'limit' => $limit,
		);

		$cache = array(
			'minutes' => $minutes,
		);

		if(Auth::check()) {

			$user = Auth::user();

			$cache['name'] = 'mainpage_user_'.$user->id;

			$section = SectionsHelper::getSection('books');
			$unwanted = ElementsHelper::getUnwanted($section, $user->id, $cache);
			$books = $this->getUserElements($section, $user, $unwanted, $sort_options, $cache);

			$section = SectionsHelper::getSection('films');
			$unwanted = ElementsHelper::getUnwanted($section, $user->id, $cache);
			$films = $this->getUserElements($section, $user, $unwanted, $sort_options, $cache);

			$section = SectionsHelper::getSection('games');
			$unwanted = ElementsHelper::getUnwanted($section, $user->id, $cache);
			$games = $this->getUserElements($section, $user, $unwanted, $sort_options, $cache);

			$section = SectionsHelper::getSection('albums');
			$unwanted = ElementsHelper::getUnwanted($section, $user->id, $cache);
			$albums = $this->getUserElements($section, $user, $unwanted, $sort_options, $cache);

		} else {

			$cache['name'] = 'mainpage_anon_';

			$section = SectionsHelper::getSection('books');
			$books = $this->getElements($section, $sort_options, $cache);

			$section = SectionsHelper::getSection('films');
			$films = $this->getElements($section, $sort_options, $cache);

			$section = SectionsHelper::getSection('games');
			$games = $this->getElements($section, $sort_options, $cache);

			$section = SectionsHelper::getSection('albums');
			$albums = $this->getElements($section, $sort_options, $cache);
		}

		$options = array(
			'header' => false,
			'paginate' => false,
			'footer' => false,
			'sort_options' => array(),
			'sort' => 'created_at',
			'order' => 'asc',
		);

		return View::make('index', array(
			'request' => $request,
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'albums' => $albums,
			'options' => $options,
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function about(Request $request) {

		$id = 1;
		$user = User::find($id);

		return View::make('static.about', array(
			'request' => $request,
			'user' => $user
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function icons(Request $request) {

		$minutes = 60;
		$icons = Cache::remember('icons', $minutes, function() {
			return Achievement::pluck('id');
		});

		return View::make('static.icons', array(
			'request' => $request,
			'icons' => $icons
		));

	}

	/**
	 * @param Section $section
	 * @param User $user
	 * @param array $unwanted
	 * @param array $sort
	 * @param array $cache
	 * @return mixed
	 */
	private function getUserElements(Section $section, User $user, array $unwanted, array $sort, array $cache) {
		if(count($cache)) {
			$var_name = $cache['name'] . '_' . $section->name;
			$elements = Cache::remember($var_name, $cache['minutes'], function () use ($section, $user, $unwanted, $sort) {
				return $section->type::where('verified', '=', 1)
					->whereNotIn($section->name . '.id', $unwanted)
					->with(array('rates' => function ($query) use ($section, $user) {
						$query
							->where('user_id', '=', $user->id)
							->where('element_type', '=', $section->type);
					}))
					->orderBy($sort['sort'], $sort['order'])
					->limit($sort['limit'])
					->get()
				;
			});
		} else {
			$elements = $section->type::where('verified', '=', 1)
				->whereNotIn($section->name . '.id', $unwanted)
				->with(array('rates' => function ($query) use ($section, $user) {
					$query
						->where('user_id', '=', $user->id)
						->where('element_type', '=', $section->type);
				}))
				->orderBy($sort['sort'], $sort['order'])
				->limit($sort['limit'])
				->get()
			;
		}
		return $elements;
	}

	/**
	 * @param Section $section
	 * @param array $sort
	 * @param array $cache
	 * @return mixed
	 */
	private function getElements(Section $section, array $sort, array $cache) {
		if(count($cache)) {
			$var_name = $cache['name'] . '_' . $section->name;
			$elements = Cache::remember($var_name, $cache['minutes'], function () use ($section, $sort) {
				return $section->type::where('verified', '=', 1)
					->orderBy($sort['sort'], $sort['order'])
					->limit($sort['limit'])
					->get()
				;
			});
		} else {
			$elements = $section->type::where('verified', '=', 1)
				->orderBy($sort['sort'], $sort['order'])
				->limit($sort['limit'])
				->get()
			;
		}
		return $elements;
	}

}
