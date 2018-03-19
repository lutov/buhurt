<?php namespace App\Http\Controllers;

use Auth;
use DB;
use View;
use Input;
use Redirect;
use App\Models\Collection;
use App\Models\Book;
use App\Models\Film;
use App\Models\Game;

class CollectionsController extends Controller {

    public function show_all()
    {
		/*
	    $genres = DB::table($this->prefix);
        return View::make('books.genres', array(
			'books' => $genres
		));
		*/
    }
	
    public function show_item($id)
    {
		$collection = Collection::find($id);

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
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'collection' => $collection
		));
    }
	
}