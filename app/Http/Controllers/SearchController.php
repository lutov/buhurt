<?php namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Event;
use App\Models\Film;
use App\Models\Game;
use App\Models\Band;
use App\Models\Album;
use App\Models\Helpers\DummyHelper;
use App\Models\Helpers\RolesHelper;
use App\Models\Helpers\TextHelper;
use App\Models\Person;
use App\Models\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class SearchController extends Controller {

	protected $prefix = 'search';

	protected $x_small_limit = 3;
	protected $small_limit = 5;
	protected $normal_limit = 28;
	protected $default_sort = 'name';
	protected $default_sort_direction = 'asc';

	/**
	 * @param Request $request
	 * @return bool|\Illuminate\Contracts\View\View
	 */
	public function everything(Request $request) {

		$presearch_query =  Input::get('query');
		$search_query =  TextHelper::prepareQuery($presearch_query);
		$order = 'name';

		if(!empty($search_query)) {

			$length = mb_strlen($search_query);
			$min_length = 3;
			if($min_length > $length) {
				$message = 'Для поиска нужно хотя бы '.$min_length.' буквы';
				return View::make($this->prefix.'.error', array(
					'message' => $message
				));

			} else {

				$search_result = $this->mainSearch($request, $search_query, $order, $presearch_query);

				if(!$search_result) {

					$cyr_search = TextHelper::cyrToLat($search_query);
					$cyr_result = $this->mainSearch($request, $cyr_search, $order, $presearch_query);

					if(!$cyr_result) {

						$lat_search = TextHelper::latToCyr($search_query);
						$lat_result = $this->mainSearch($request, $lat_search, $order, $presearch_query);

						if(!$lat_result) {

							// TODO: Add suggestions
							//echo $search_query;
							$query_words = explode(' ', $search_query);
							foreach($query_words as $query) {
								$word_result = $this->mainSearch($request, $query, $order, $search_query);
								if($word_result) {return $word_result;}
							}

							$message = 'По запросу «'.TextHelper::getCleanName($search_query).'» ничего не найдено.';

							if (RolesHelper::isAdmin($request)) {

								$message .= '<p><a href="/admin/add/">Добавить элемент</a>?</p>';

								$message .= DummyHelper::getQuickAddLinks($search_query);

							} else {

								$not_found = new NotFound();

								$user_id = 1;
								if (Auth::check()) {
									$user_id = Auth::user()->id;
								}

								$not_found->user_id = $user_id;
								$not_found->search = $search_query;
								$not_found->save();

								$type = "NotFound";
								$event = new Event();
								$event->event_type = 'Search';
								$event->element_type = $type;
								$event->element_id = 1;
								$event->user_id = $user_id;
								$event->name = 'Не найдено'; //«'.$search.'»';
								$event->text = $search_query;
								$event->save();

								if(Auth::check()) {

									$message .= DummyHelper::getQuickAddLinks($search_query);

								} else {

									$message .= '</p>Мы постараемся добавить произведение, которое вы искали, в ближайшее время.</p>';

									$message .= DummyHelper::regToAdd();

								}

							}

							return View::make($this->prefix . '.error', array(
								'request' => $request,
								'message' => $message
							));

						} else {return $lat_result;}

					} else {return $cyr_result;}

				} else {

					return $search_result;

				}

			}

		} else {

			$message = 'Кажется, запрос пуст';
			return View::make($this->prefix.'.error', array(
				'request' => $request,
				'message' => $message
			));

		}

	}

	/**
	 * @param Request $request
	 * @param $search_query
	 * @param $order
	 * @param $presearch_query
	 * @return bool|\Illuminate\Contracts\View\View
	 */
	private function mainSearch(Request $request, $search_query, $order, $presearch_query) {

		$limit = 100;

		$persons = Person::where('name', 'like', '%' . $search_query . '%')
			->orderBy($order)
			->limit($limit)
			->get()
		;

		$books = Book::where(function($query) use ($search_query) {
			$query
				->where('name', 'like', '%' . $search_query . '%')
				->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->limit($limit)->get();

		$films = Film::where(function($query) use ($search_query) {
			$query
				->where('name', 'like', '%' . $search_query . '%')
				->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->limit($limit)->get();

		$games = Game::where(function($query) use ($search_query) {
			$query
				->where('name', 'like', '%' . $search_query . '%')
				->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->limit($limit)->get();

		$albums = Album::where(function($query) use ($search_query) {
			$query
				->where('name', 'like', '%' . $search_query . '%')
				//->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->limit($limit)->get();

		$bands = Band::where(function($query) use ($search_query) {
			$query
				->where('name', 'like', '%' . $search_query . '%')
				//->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->limit($limit)->get();

		if(!count($persons) && !count($books) && !count($films) && !count($games) && !count($albums) && !count($bands)) {

			return false;

		} else {

			$options = array(
				'header' => true,
				'paginate' => false,
				'footer' => true,
				'sort_list' => array(),
				'sort' => 'name',
				'order' => 'asc',
			);

			return View::make($this->prefix . '.index', array(
				'request' => $request,
				'query' => $presearch_query,
				'persons' => $persons,
				'books' => $books,
				'films' => $films,
				'games' => $games,
				'albums' => $albums,
				'bands' => $bands,
				'options' => $options,
			));

		}

	}

	/**
	 * @param array $result
	 * @param string $query
	 * @return array
	 */
	private function getNameList(array $result = array(), string $query = '') {

		$limit = 3;

		$sections = array(
			'persons' => new Person(),
			'books' => new Book(),
			'films' => new Film(),
			'games' => new Game(),
			'albums' => new Album(),
			'bands' => new Band(),
		);

		if(!empty($query)) {

			foreach($sections as $section => $elements) {

				$sections[$section] = $elements->where('name', 'like', '%' . $query . '%')
					->limit($limit)
					->pluck('name')
				;

			}

			foreach($sections as $section => $elements) {

				foreach($elements as $key => $value) {
					$result[] = $value;
				}

			}

		}

		return $result;

	}

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function everythingJson() {

		$result = array();

		$raw_query =  urldecode(Input::get('term'));
		$query =  TextHelper::prepareQuery($raw_query);

		$result = $this->getNameList($result, $query);

		if(empty($result)) {

			$lat_query = TextHelper::latToCyr($query);
			$result = $this->getNameList($result, $lat_query);

		}

		if(empty($result)) {

			$cyr_query = TextHelper::cyrToLat($query);
			$result = $this->getNameList($result, $cyr_query);

		}

		return View::make($this->prefix . '.json', array(
			'result' => $result,
		));

	}

	/**
	 * @param string $query
	 * @return array
	 */
	private function getIDNameList(string $query = '') {

		$limit = 3;

		$result = array();

		$sections = array(
			'persons' => new Person(),
			'books' => new Book(),
			'films' => new Film(),
			'games' => new Game(),
			'albums' => new Album(),
			'bands' => new Band(),
		);

		if(!empty($query)) {

			foreach($sections as $section => $elements) {

				$sections[$section] = $elements->where('name', 'like', '%' . $query . '%')
					->limit($limit)
					->get()
				;

			}

			foreach($sections as $section => $elements) {

				foreach($elements as $key => $value) {
					$result[$section][$value->id]['id'] = $value->id;
					$result[$section][$value->id]['name'] = $value->name;
				}

			}

		}

		return $result;

	}

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getJson() {

		$raw_query = urldecode(Input::get('query'));
		$query =  TextHelper::prepareQuery($raw_query);

		$result = $this->getIDNameList($query);

		return View::make($this->prefix . '.list_json', array(
			'result' => $result,
		));

	}

}