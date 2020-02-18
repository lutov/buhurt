<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\Data\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class GenresController extends Controller {

	protected string $section = 'genres';

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function list(Request $request) {

		$section = SectionsHelper::getSection($this->section);
		$element = new $section->type;

		$sort = $request->get('sort', 'name');
		$order = $request->get('order', 'asc');
		$limit = 28;

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$sort_options = array(
			'name' => 'Имя',
		);

		$titles = array();

		$type = 'Book';
		$books = $this->getElementsByType($type, $element, $sort, $order, $limit);
		if($books->count()) {
			$titles['books']['name'] = 'Книги';
			$titles['books']['count'] = $books->count();
		}

		$type = 'Film';
		$films = $this->getElementsByType($type, $element, $sort, $order, $limit);
		if($films->count()) {
			$titles['films']['name'] = 'Фильмы';
			$titles['films']['count'] = $films->count();
		}

		$type = 'Game';
		$games = $this->getElementsByType($type, $element, $sort, $order, $limit);
		if($games->count()) {
			$titles['games']['name'] = 'Игры';
			$titles['games']['count'] = $games->count();
		}

		$type = 'Album';
		$albums = $this->getElementsByType($type, $element, $sort, $order, $limit);
		if($albums->count()) {
			$titles['albums']['name'] = 'Альбомы';
			$titles['albums']['count'] = $albums->count();
		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'sort_options' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make($this->section.'.index', array(
			'request' => $request,
			'titles' => $titles,
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'albums' => $albums,
			'section' => $section,
			'options' => $options,
		));

	}

	/**
	 * @param Request $request
	 * @param int $id
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function item(Request $request, int $id) {

		$section = SectionsHelper::getSection($this->section);
		$element = $section->type::find($id);

		if(isset($element->id)) {

			$sort = $request->get('sort', 'name');
			$order = $request->get('order', 'asc');
			$limit = 28;

			$sort = TextHelper::checkSort($sort);
			$order = TextHelper::checkOrder($order);

			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'alt_name' => 'Оригинальное название',
				'year' => 'Год'
			);

			$titles = array();

			$elements_section = 'books';
			$books = $this->getElementsByGenre($elements_section, $element, $sort, $order, $limit);
			if($books->count()) {
				$titles['books']['name'] = 'Книги';
				$titles['books']['count'] = $this->countElementsByGenre($elements_section, $element);
			}

			$elements_section = 'films';
			$films = $this->getElementsByGenre($elements_section, $element, $sort, $order, $limit);
			if($films->count()) {
				$titles['films']['name'] = 'Фильмы';
				$titles['films']['count'] = $this->countElementsByGenre($elements_section, $element);
			}

			$elements_section = 'games';
			$games = $this->getElementsByGenre($elements_section, $element, $sort, $order, $limit);
			if($games->count()) {
				$titles['games']['name'] = 'Игры';
				$titles['games']['count'] = $this->countElementsByGenre($elements_section, $element);
			}

			$elements_section = 'albums';
			$albums = $this->getElementsByGenre($elements_section, $element, $sort, $order, $limit);
			if($albums->count()) {
				$titles['albums']['name'] = 'Альбомы';
				$titles['albums']['count'] = $this->countElementsByGenre($elements_section, $element);
			}

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_options' => $sort_options,
				'sort' => $sort,
				'order' => $order,
			);

			return View::make($this->section.'.item', array(
				'request' => $request,
				'titles' => $titles,
				'element' => $element,
				'section' => $section,
				'books' => $books,
				'films' => $films,
				'games' => $games,
				'albums' => $albums,
				'options' => $options
			));

		} else {

			return Redirect::to('/'.$this->section);

		}

	}

	/**
	 * @param string $type
	 * @param $element
	 * @param string $sort
	 * @param string $order
	 * @param int $limit
	 * @return mixed
	 */
	private function getElementsByType(string $type, $element, string $sort, string $order, int $limit) {
		return $element->where('element_type', $type)
			->orderBy($sort, $order)
			->paginate($limit)
		;
	}

	/**
	 * @param string $section
	 * @param $element
	 * @param string $sort
	 * @param string $order
	 * @param int $limit
	 * @return mixed
	 */
	private function getElementsByGenre(string $section, $element, string $sort, string $order, int $limit) {
		return $element->{$section}()
			->orderBy($sort, $order)
			->paginate($limit)
		;
	}

	/**
	 * @param string $section
	 * @param $element
	 * @return mixed
	 */
	private function countElementsByGenre(string $section, $element) {
		$minutes = 60;
		$var_name = 'genre_'.$element->id.'_count';
		return Cache::remember($var_name, $minutes, function () use ($section, $element) {
			return $element->{$section}()->count();
		});
	}
	
}