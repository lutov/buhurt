<?php namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Data\Book;
use App\Models\User\Event;
use App\Models\Data\Film;
use App\Models\Data\Game;
use App\Models\Data\Band;
use App\Models\Data\Album;
use App\Helpers\DummyHelper;
use App\Helpers\RolesHelper;
use App\Helpers\TextHelper;
use App\Models\Data\Person;
use App\Models\Data\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use YaMetrika;

class SearchController extends Controller {

	protected string $section = 'search';

	/**
	 * @param Request $request
	 * @return bool|\Illuminate\Contracts\View\View
	 */
	public function everything(Request $request) {

		$presearch_query =  $request->get('query');
		$search_query =  TextHelper::prepareQuery($presearch_query);
		$order = 'name';

		if(!empty($search_query)) {

			$length = mb_strlen($search_query);
			$min_length = 3;
			if($min_length > $length) {
				$message = 'Для поиска нужно хотя бы '.$min_length.' буквы';
				return View::make($this->section.'.error', array(
					'request' => $request,
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

							$message = 'По запросу «'.TextHelper::getCleanName($search_query).'» ничего не найдено.';

							if (RolesHelper::isAdmin($request)) {

								$message .= '<p><a href="/admin/add/">Добавить элемент</a>?</p>';

								$message .= DummyHelper::getQuickAddLinks($search_query, $request);

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
								$event->text = '<a href="/search?query='.$search_query.'">'.$search_query.'</a>';
								$event->save();

								if(Auth::check()) {

									$message .= DummyHelper::getQuickAddLinks($search_query, $request);

								} else {

									$message .= '</p>Мы постараемся добавить произведение, которое вы искали, в ближайшее время.</p>';

									$message .= DummyHelper::regToAdd();

								}

							}

							return View::make($this->section . '.error', array(
								'request' => $request,
								'message' => $message
							));

						} else {return $lat_result;}

					} else {return $cyr_result;}

				} else {return $search_result;}

			}

		} else {
			$message = 'Кажется, запрос пуст';
			return View::make($this->section.'.error', array(
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

		$persons = $books = $films = $games = $albums = $bands = $companies = $genres = array();

		$searchable = array(
			'persons' => array(
				'name' => 'Персоны',
				'section' => 'persons',
				'type' =>'Person',
				'fields' => array('name')
			),
			'books' => array(
				'name' => 'Книги',
				'section' => 'books',
				'type' =>'Book',
				'fields' => array('name', 'alt_name')
			),
			'films' => array(
				'name' => 'Фильмы',
				'section' => 'films',
				'type' =>'Film',
				'fields' => array('name', 'alt_name')
			),
			'games' => array(
				'name' => 'Игры',
				'section' => 'games',
				'type' =>'Game',
				'fields' => array('name', 'alt_name')
			),
			'albums' => array(
				'name' => 'Альбомы',
				'section' => 'albums',
				'type' =>'Album',
				'fields' => array('name')
			),
			'band' => array(
				'name' => 'Группы',
				'section' => 'bands',
				'type' =>'Band',
				'fields' => array('name')
			),
			'companies' => array(
				'name' => 'Компании',
				'section' => 'companies',
				'type' =>'Company',
				'fields' => array('name')
			),
			'genres' => array(
				'name' => 'Жанры',
				'section' => 'genres',
				'type' =>'Genre',
				'fields' => array('name')
			),
		);

		foreach($searchable as $entity) {
			${$entity['section']} = $entity['type']::where(
				function ($query) use ($entity, $search_query) {
					foreach($entity['fields'] as $field) {
						$query->orWhere($field, 'like', '%'.$search_query.'%');
					}
				})
				->orderBy($order)
				->limit($limit)
				->get()
			;
		}

		if(count($persons) || count($books) || count($films) || count($games) || count($albums) || count($bands) || count($companies) || count($genres)) {

			foreach($searchable as $entity) {
				$entities = $entity['section'];
				$name = $entity['name'];
				if (count($$entities)) {
					$titles[$entities]['name'] = $name;
					$titles[$entities]['count'] = count($$entities);
				}
			}
			uasort($titles, array('TextHelper', 'compareReverseCount'));

			$options = array(
				'header' => true,
				'paginate' => false,
				'footer' => true,
				'sort_options' => array(),
				'sort' => 'name',
				'order' => 'asc',
			);

			return View::make($this->section.'.index', array(
				'request' => $request,
				'query' => $presearch_query,
				'titles' => $titles,
				'persons' => $persons,
				'books' => $books,
				'films' => $films,
				'games' => $games,
				'albums' => $albums,
				'bands' => $bands,
				'companies' => $companies,
				'genres' => $genres,
				'options' => $options,
			));

		}

		return false;

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
	 * @param Request $request
	 * @return mixed
	 */
	public function everythingJson(Request $request) {

		$result = array();

		$raw_query =  urldecode($request->get('term'));
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

		return View::make($this->section . '.json', array(
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
				$sections[$section] = $elements
					->where('name', 'like', '%'.$query.'%')
					->orWhere('alt_name', 'like', '%'.$query.'%')
					->limit($limit)
					->get()
				;
			}
			foreach($sections as $section => $elements) {
				foreach($elements as $key => $value) {
					$result[$section][$value->id]['id'] = $value->id;
					$result[$section][$value->id]['name'] = $value->name;
					$result[$section][$value->id]['alt_name'] = $value->alt_name;
				}
			}
		}
		return $result;
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function api(Request $request) {

		$raw_query = urldecode($request->get('query'));
		$query =  TextHelper::prepareQuery($raw_query);

		$counter = new YaMetrika(env('YA_METRIKA')); // Номер счётчика Метрики
		$counter->hit($request->fullUrl());

		$data = $this->getIDNameList($query);

		$result['status'] = (count($data)) ? 'OK' : 'Not Found';
		$result['count'] = count($data);
		$result['data'] = $data;
		$result['url'] = $request->fullUrl();
		$result['errors'] = null;

		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($result); die();

	}

}