<?php namespace App\Http\Controllers;

use App\Models\Section;
use Auth;
use DB;
use Illuminate\Http\Request;
use View;
use Input;
use Redirect;
use App\Models\Collection;
use App\Models\Book;
use App\Models\Film;
use App\Models\Game;

class CollectionsController extends Controller {

	private $prefix = 'collections';

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function show_all(Request $request) {

		$section = $this->prefix;

		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', $section.'.name');
		$sort_direction = Input::get('sort_direction', 'asc');
		$limit = 28;

		$sort_options = array(
			$section.'.created_at' => 'Время добавления',
			$section.'.name' => 'Название',
			$section.'.alt_name' => 'Оригинальное название',
			$section.'.year' => 'Год'
		);

		$elements = Collection::orderBy($sort, $sort_direction)
			->paginate($limit)
		;

		return View::make($this->prefix.'.index', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'ru_section' => $ru_section,
		));

	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
    public function show_item(Request $request, $id) {

		$section = $this->prefix;

		$collection = Collection::find($id);

		$cover = 0;
		$file_path = public_path() . '/data/img/covers/'.$section.'/'.$id.'.jpg';
		if (file_exists($file_path)) {
			$cover = $id;
		}

		$sort_direction = 'asc';
		$limit = 28;

		$books = Book::select('books.*')
			->leftJoin('elements_collections', 'books.id', '=', 'elements_collections.element_id')
			->where('collection_id', '=', $id)
			->where('element_type', '=', 'Book')
			->orderBy('name', $sort_direction)
			//>remember(60)
			//->get()
			->paginate($limit)
		;

		$films = Film::select('films.*')
			->leftJoin('elements_collections', 'films.id', '=', 'elements_collections.element_id')
			->where('collection_id', '=', $id)
			->where('element_type', '=', 'Film')
			->orderBy('name', $sort_direction)
			//->remember(60)
			//->get()
			->paginate($limit)
		;

		$games = Game::select('games.*')
			->leftJoin('elements_collections', 'games.id', '=', 'elements_collections.element_id')
			->where('collection_id', '=', $id)
			->where('element_type', '=', 'Game')
			->orderBy('name', $sort_direction)
			//->remember(60)
			//->get()
			->paginate($limit)
		;

		return View::make('collections.item', array(
			'request' => $request,
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'section' => $section,
			'element' => $collection,
			'cover' => $cover,
		));
    }
	
}