<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Data\Collection;
use App\Models\Data\Book;
use App\Models\Data\Film;
use App\Models\Data\Game;

class CollectionsController extends Controller {

	private $prefix = 'collections';

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function list(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		$sort = $request->get('sort', 'name');
		$order = $request->get('order', 'asc');
		$limit = 28;

		$sort_options = array(
			'name' => 'Название',
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$elements = Collection::orderBy($sort, $order)
			->paginate($limit)
		;

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
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
	 * @return \Illuminate\Contracts\View\View
	 */
    public function item(Request $request, $id) {

		$section = SectionsHelper::getSection($this->prefix);

		$collection = Collection::find($id);

		$cover = 0;
		$file_path = public_path() . '/data/img/covers/'.$section->alt_name.'/'.$id.'.jpg';
		if (file_exists($file_path)) {
			$cover = $id;
		}

		$order = 'asc';
		$limit = 28;

		$books = Book::select('books.*')
			->leftJoin('elements_collections', 'books.id', '=', 'elements_collections.element_id')
			->where('collection_id', '=', $id)
			->where('element_type', '=', 'Book')
			->orderBy('name', $order)
			->paginate($limit)
		;

		$films = Film::select('films.*')
			->leftJoin('elements_collections', 'films.id', '=', 'elements_collections.element_id')
			->where('collection_id', '=', $id)
			->where('element_type', '=', 'Film')
			->orderBy('name', $order)
			->paginate($limit)
		;

		$games = Game::select('games.*')
			->leftJoin('elements_collections', 'games.id', '=', 'elements_collections.element_id')
			->where('collection_id', '=', $id)
			->where('element_type', '=', 'Game')
			->orderBy('name', $order)
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