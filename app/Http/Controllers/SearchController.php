<?php namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Event;
use App\Models\Film;
use App\Models\Game;
use App\Models\Band;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Helpers\DatatypeHelper;
use App\Models\Helpers\SectionsHelper;
use App\Models\Helpers\TextHelper;
use App\Models\Person;
use App\Models\Company;
use App\Models\Helpers;
use App\Models\Country;
use App\Models\NotFound;
use App\Models\Platform;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller {

	protected $prefix = 'search';

	protected $x_small_limit = 3;
	protected $small_limit = 5;
	protected $normal_limit = 28;
	protected $default_sort = 'name';
	protected $default_sort_direction = 'asc';

	/**
	 * @return mixed
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

				$search_result = $this->main_search($request, $search_query, $order, $presearch_query);

				if(!$search_result) {

					$search_query2ru = Helpers::switch2ru($search_query);
					//die($search_query2ru);
					$search_result2ru = $this->main_search($request, $search_query2ru, $order, $search_query2ru);
					//die($search_result2ru);

					if(!$search_result2ru) {

						$search_query2en = Helpers::switch2en($search_query);
						$search_result2en = $this->main_search($request, $search_query2en, $order, $search_query2en);

						if(!$search_result2en) {

							//$message = 'Кажется, по этому запросу ничего не найдено. <a href="/admin/add/">Добавить элемент</a>?';
							$not_found = new NotFound();

							$user_id = 1;
							if (Auth::check()) {
								$user_id = Auth::user()->id;
							}
							$search = $search_query;

							$not_found->user_id = $user_id;
							$not_found->search = $search;
							$not_found->save();

							$type = "NotFound";
							$event = new Event();
							$event->event_type = 'Search';
							$event->element_type = $type;
							$event->element_id = 1;
							$event->user_id = $user_id;
							$event->name = 'Не найдено'; //«'.$search.'»';
							$event->text = $search;
							$event->save();

							$message = 'Кажется, по этому запросу ничего не найдено.';
							if (Helpers::is_admin()) {
								$message .= ' 
									<a href="/admin/add/">Добавить элемент</a>?</p>
									
									<p>
									Быстро создать
									<ol>
									
										<li>
											<a href="/admin/q_add/books/?new_name='.urlencode($search).'">Книгу</a>
											<ul>
											
												<li>
													<a href="/admin/q_add/books/?new_name='.urlencode($search).'&template=marvel_book">
														Marvel Comics
													</a>
												</li>
											
												<li>
													<a href="/admin/q_add/books/?new_name='.urlencode($search).'&template=dc_book">
														DC Comics
													</a>
												</li>
											
												<li>
													<a href="/admin/q_add/books/?new_name='.urlencode($search).'&template=image_book">
														Image Comics
													</a>
												</li>
											
												<li>
													<a href="/admin/q_add/books/?new_name='.urlencode($search).'&template=valiant_book">
														Valiant Comics
													</a>
												</li>
											
											</ul>
										</li>
									
										<li>
											<a href="/admin/q_add/films/?new_name='.urlencode($search).'">Фильм</a>
											
											<ul>
											
												<li>
													<a href="/admin/q_add/films/?new_name='.urlencode($search).'&template=anime">
														Аниме
													</a>
												</li>
											
												<li>
													<a href="/admin/q_add/films/?new_name='.urlencode($search).'&template=marvel_film">
														Marvel Comics
													</a>
												</li>
											
												<li>
													<a href="/admin/q_add/films/?new_name='.urlencode($search).'&template=dc_film">
														DC Comics
													</a>
												</li>
											
											</ul>
																					
																																									
										</li>
									
										<li>
											<a href="/admin/q_add/games/?new_name='.urlencode($search).'">Игру</a>
										</li>	
									
										<li>
											<a href="/admin/q_add/albums/?new_name='.urlencode($search).'">Альбом</a>
										</li>
										
									</ol>
								';
							} else {
								$message .= ' Мы постараемся добавить произведение, которое вы искали, в ближайшее время.';
							}

							return View::make($this->prefix . '.error', array(
								'request' => $request,
								'message' => $message
							));

						} else {return $search_result2en;}

					} else {return $search_result2ru;}

				} else {return $search_result;}
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
	 * @return \Illuminate\Contracts\View\View
	 */
	public function everything_json() {

		$result_array = array();
		$persons = $books = $films = $games = $albums = $bands = array();

		$limit = 3;

		$presearch_query =  urldecode(Input::get('term'));
		$search_query =  TextHelper::prepareQuery($presearch_query);

		if(!empty($search_query)) {

			$persons = Person::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->pluck('name')
			;

			$books = Book::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->pluck('name')
			;

			$films = Film::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->pluck('name')
			;

			$games = Game::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->pluck('name')
			;

			$albums = Album::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->pluck('name')
			;

			$bands = Band::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->pluck('name')
			;

		} else {
			//$persons = $books = $films = $games = $albums = $bands = array();
		}

		foreach($persons 	as $key => $value) 		{$result_array[] = $value;}
		foreach($books 		as $key => $value) 		{$result_array[] = $value;}
		foreach($films 		as $key => $value) 		{$result_array[] = $value;}
		foreach($games 		as $key => $value) 		{$result_array[] = $value;}
		foreach($albums 	as $key => $value) 		{$result_array[] = $value;}
		foreach($bands 		as $key => $value) 		{$result_array[] = $value;}

		$result = $result_array;

		//echo $result;
		return View::make($this->prefix . '.json', array(
			'result' => $result,
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function advanced(Request $request) {

		return View::make($this->prefix.'.advanced.index', array(
			'request' => $request
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function persons(Request $request) {

		$sub_section = '';
		$section = 'persons';
		$title = 'Персоны';
		$subtitle = '';

		$sort = $this->default_sort;
		$sort_direction = $this->default_sort_direction;
		$limit = $this->normal_limit;

		$elements = Person::orderBy($sort, $sort_direction)
			//->remember(60)
			->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function companies(Request $request) {

		$sub_section = '';
		$section = 'companies';
		$title = 'Компании';
		$subtitle = '';

		$sort = $this->default_sort;
		$sort_direction = $this->default_sort_direction;
		$limit = $this->normal_limit;

		$elements = Company::orderBy($sort, $sort_direction)
			//->remember(60)
			->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function bands(Request $request) {

		$sub_section = '';
		$section = 'bands';
		$title = 'Группы';
		$subtitle = '';

		$sort = $this->default_sort;
		$sort_direction = $this->default_sort_direction;
		$limit = $this->normal_limit;

		$elements = Band::orderBy($sort, $sort_direction)
			//->remember(60)
			->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function collections(Request $request) {

		$sub_section = '';
		$section = 'collections';
		$title = 'Коллекции';
		$subtitle = '';

		$sort = $this->default_sort;
		$sort_direction = $this->default_sort_direction;
		$limit = $this->normal_limit;

		$elements = Collection::orderBy($sort, $sort_direction)
			//->remember(60)
			->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @return \Illuminate\Contracts\View\View
	 */
	public function genres(Request $request, $section) {

		$sub_section = 'genres';
		$title = Helpers::get_section_name($section);
		$subtitle = 'Жанры';
		$type = Helpers::get_section_type($section);

		$sort = $this->default_sort;
		$sort_direction = $this->default_sort_direction;
		$limit = $this->normal_limit;

		$elements = Genre::where('element_type', '=', $type)
			->orderBy($sort, $sort_direction)
			//->remember(60)
			->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function countries(Request $request) {

		$sub_section = 'countries';
		$section = 'films';
		$title = 'Страны';
		$subtitle = '';

		$sort = $this->default_sort;
		$sort_direction = $this->default_sort_direction;
		$limit = $this->normal_limit;

		$elements = Country::orderBy($sort, $sort_direction)
			//->remember(60)
			->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function platforms(Request $request) {

		$sub_section = 'platforms';
		$section = 'games';
		$title = 'Игровые платформы';
		$subtitle = '';

		$sort = $this->default_sort;
		$sort_direction = $this->default_sort_direction;
		$limit = $this->normal_limit;

		$elements = Platform::orderBy($sort, $sort_direction)
			//->remember(60)
			->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));
	}

	/**
	 * @param Request $request
	 * @param $section
	 * @return \Illuminate\Contracts\View\View
	 */
	public function years(Request $request, $section) {

		$sub_section = 'years';
		$title = SectionsHelper::getSectionName($section);
		$subtitle = 'Года';

		$sort = 'year';
		$sort_direction = 'desc';
		//$limit = $this->normal_limit;

		$elements = DB::table($section)
			//->select('year')
			->selectRaw('`year` as `id`, `year` as `name`')
			->distinct()
			->orderBy($sort, $sort_direction)
			//->remember(60)
			->get()
			//->paginate($limit)
		;

		return View::make($this->prefix.'.advanced.years_list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));

	}


	public function person_name() {

		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$persons = Person::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get()
			;
			$result = '['.DatatypeHelper::objectToJsArray($persons).']';
		}

		echo $result;
	}

	public function company_name() {

		$limit = $this->small_limit;

		$query = urldecode(urldecode(Input::get('term')));

		//echo $query;

		$result = '';

		if(!empty($query)) {

			$companies = Company::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get()
			;
			$result = '['.DatatypeHelper::objectToJsArray($companies).']';

		}

		echo $result;

	}

	public function collection_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$collections = Collection::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get()
			;
			$result = '['.DatatypeHelper::objectToJsArray($collections).']';
		}

		echo $result;
	}

	public function platform_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$platforms = Platform::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get()
			;
			$result = '['.DatatypeHelper::objectToJsArray($platforms).']';
		}

		echo $result;
	}


	public function country_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$countries = Country::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get()
			;
			$result = '['.DatatypeHelper::objectToJsArray($countries).']';
		}

		echo $result;
	}

	public function book_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$books = Book::where('name', 'like', '%'.$query.'%')
				//->where('element_type', '=', 'Book')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($books).']';
		}

		echo $result;
	}
	public function book_genre()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$genres = Genre::where('name', 'like', '%'.$query.'%')
				->where('element_type', '=', 'Book')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($genres).']';
		}

		echo $result;
	}

	public function film_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$films = Film::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($films).']';
		}

		echo $result;
	}
	public function film_genre()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$genres = Genre::where('name', 'like', '%'.$query.'%')
				->where('element_type', '=', 'Film')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($genres).']';
		}

		echo $result;
	}

	public function game_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$games = Game::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($games).']';
		}

		echo $result;
	}
	public function game_genre()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$genres = Genre::where('name', 'like', '%'.$query.'%')
				->where('element_type', '=', 'Game')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($genres).']';
		}

		echo $result;
	}

	public function album_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$albums = Album::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($albums).']';
		}

		echo $result;
	}
	public function band_name()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$bands = Band::where('name', 'like', '%'.$query.'%')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($bands).']';
		}

		echo $result;
	}
	public function album_genre()
	{
		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

		$result = '';

		if(!empty($query))
		{
			$genres = Genre::where('name', 'like', '%'.$query.'%')
				->where('element_type', '=', 'Album')
				->limit($limit)
				//->remember(60)
				->get();
			$result = '['.DatatypeHelper::objectToJsArray($genres).']';
		}

		echo $result;
	}

	/**
	 * @param Request $request
	 * @param $search_query
	 * @param $order
	 * @param $presearch_query
	 * @return bool|\Illuminate\Contracts\View\View
	 */
	private function main_search(Request $request, $search_query, $order, $presearch_query) {

		$persons = Person::where('name', 'like', '%' . $search_query . '%')->orderBy($order)->get(); //->remember(5)
		$books = Book::where(function($query) use ($search_query)
		{
			$query
				->where('name', 'like', '%' . $search_query . '%')
				->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->get(); //->remember(5)
		$films = Film::where(function($query) use ($search_query)
		{
			$query
				->where('name', 'like', '%' . $search_query . '%')
				->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->get(); //->remember(5)
		//echo '<pre>'.print_r($films, true).'</pre>';
		//$films = Film::where('name', 'like', '%' . $search_query . '%')->get();
		$games = Game::where(function($query) use ($search_query)
		{
			$query
				->where('name', 'like', '%' . $search_query . '%')
				->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->get(); //->remember(5)
		//echo '<pre>'.print_r($games, true).'</pre>';
		$albums = Album::where(function($query) use ($search_query)
		{
			$query
				->where('name', 'like', '%' . $search_query . '%')
				//->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->get(); //->remember(5)
		//echo '<pre>'.print_r($games, true).'</pre>';
		$bands = Band::where(function($query) use ($search_query)
		{
			$query
				->where('name', 'like', '%' . $search_query . '%')
				//->orWhere('alt_name', 'like', '%' . $search_query . '%')
			;
		})->orderBy($order)->get(); //->remember(5)
		//echo '<pre>'.print_r($games, true).'</pre>';

		if(!count($persons) && !count($books) && !count($films) && !count($games) && !count($albums) && !count($bands)) {

			return false;

		} else {

			return View::make($this->prefix . '.index', array(
				'request' => $request,
				'query' => $presearch_query,
				'persons' => $persons,
				'books' => $books,
				'films' => $films,
				'games' => $games,
				'albums' => $albums,
				'bands' => $bands
			));

		}

	}

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function get_list_json() {

		$result_array = array();
		$persons = $books = $films = $games = $albums = $bands = array();

		$limit = 3;

		$presearch_query = urldecode(Input::get('query'));
		$search_query =  TextHelper::prepareQuery($presearch_query);

		if(!empty($search_query)) {

			$persons = Person::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->get()
			;

			$books = Book::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->get()
			;

			$films = Film::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->get()
			;

			$games = Game::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->get()
			;

			$albums = Album::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->get()
			;

			$bands = Band::where('name', 'like', '%' . $search_query . '%')
				->limit($limit)
				->get()
			;

		} else {
			//$persons = $books = $films = $games = $albums = $bands = array();
		}

		foreach($persons as $key => $value) {
			$result_array['persons'][$value->id]['id'] = $value->id;
			$result_array['persons'][$value->id]['name'] = $value->name;
		}

		foreach($books as $key => $value) {
			$result_array['books'][$value->id]['id'] = $value->id;
			$result_array['books'][$value->id]['name'] = $value->name;
		}

		foreach($films as $key => $value) {
			$result_array['films'][$value->id]['id'] = $value->id;
			$result_array['films'][$value->id]['name'] = $value->name;
		}

		foreach($games as $key => $value) {
			$result_array['games'][$value->id]['id'] = $value->id;
			$result_array['games'][$value->id]['name'] = $value->name;
		}

		foreach($albums as $key => $value) {
			$result_array['albums'][$value->id]['id'] = $value->id;
			$result_array['albums'][$value->id]['name'] = $value->name;
		}

		foreach($bands as $key => $value) {
			$result_array['bands'][$value->id]['id'] = $value->id;
			$result_array['bands'][$value->id]['name'] = $value->name;
		}

		$result = $result_array;

		//echo $result;
		return View::make($this->prefix . '.list_json', array(
			'result' => $result,
		));

	}
}