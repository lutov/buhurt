<?php namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Session;
use View;
use Cache;
use App\Models\Helpers;
use App\Models\Book;
use App\Models\Film;
use App\Models\Game;
use App\Models\Album;
use App\Models\Achievement;
use App\Models\User;
use App\Models\Wanted;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index(Request $request) {

		$limit = 9;
		$minutes = 10;
		$order_by = 'updated_at';

		if(Auth::check()) {
			$user_id = Auth::user()->id;

			$not_wanted_books = Cache::remember('not_wanted_books_mainpage_auth'.$user_id, $minutes, function() use ($user_id)
			{
				return Wanted::select('element_id')
					->where('element_type', '=', 'Book')
					->where('not_wanted', '=', 1)
					->where('user_id', '=', $user_id)
					->pluck('element_id')
				;
			});

			$not_wanted_films = Cache::remember('not_wanted_films_mainpage_auth'.$user_id, $minutes, function() use ($user_id)
			{
				return Wanted::select('element_id')
					->where('element_type', '=', 'Film')
					->where('not_wanted', '=', 1)
					->where('user_id', '=', $user_id)
					//->remember(10)
					->pluck('element_id')
				;
			});

			$not_wanted_games = Cache::remember('not_wanted_games_mainpage_auth'.$user_id, $minutes, function() use ($user_id)
			{
				return Wanted::select('element_id')
					->where('element_type', '=', 'Game')
					->where('not_wanted', '=', 1)
					->where('user_id', '=', $user_id)
					//->remember(10)
					->pluck('element_id')
				;
			});

			$not_wanted_albums = Cache::remember('not_wanted_albums_mainpage_auth'.$user_id, $minutes, function() use ($user_id)
			{
				return Wanted::select('element_id')
					->where('element_type', '=', 'Album')
					->where('not_wanted', '=', 1)
					->where('user_id', '=', $user_id)
					//->remember(10)
					->pluck('element_id')
				;
			});

			$books = Cache::remember('books_mainpage_auth'.$user_id, $minutes, function() use ($user_id, $not_wanted_books, $order_by, $limit)
			{
				return Book::with(array('rates' => function($query) use($user_id)
						{
							$query
								->where('user_id', '=', $user_id)
								->where('element_type', '=', 'Book')
							;
						})
					)
					->whereNotIn('books.id', $not_wanted_books)
					->orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});

			$films = Cache::remember('films_mainpage_auth'.$user_id, $minutes, function() use ($user_id, $not_wanted_films, $order_by, $limit) {
				return Film::with(array('rates' => function ($query) use ($user_id) {
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', 'Film');
						})
					)
					->whereNotIn('films.id', $not_wanted_films)
					->orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});

			$games = Cache::remember('games_mainpage_auth'.$user_id, $minutes, function() use ($user_id, $not_wanted_games, $order_by, $limit) {
				return Game::with(array('rates' => function($query) use($user_id) {
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', 'Game')
						;
					})
				)
					->whereNotIn('games.id', $not_wanted_games)
					->orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});

			$albums = Cache::remember('albums_mainpage_auth'.$user_id, $minutes, function() use ($user_id, $not_wanted_albums, $order_by, $limit) {
				return Album::with(array('rates' => function($query) use($user_id) {
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', 'Album')
						;
					})
				)
					->whereNotIn('games.id', $not_wanted_albums)
					->orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});
		}
		else
		{
			//$news = News::orderBy('created_at', 'desc')->limit($limit)->get();

			$books = Cache::remember('books_mainpage_unauth', $minutes, function() use ($limit, $order_by) {
				return Book::orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});
			$films = Cache::remember('films_mainpage_unauth', $minutes, function() use ($limit, $order_by) {
				return Film::orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});
			$games = Cache::remember('games_mainpage_unauth', $minutes, function() use ($limit, $order_by) {
				return Game::orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});
			$albums = Cache::remember('albums_mainpage_unauth', $minutes, function() use ($limit, $order_by) {
				return Album::orderBy($order_by, 'desc')
					->limit($limit)
					//->remember(10)
					->get()
				;
			});
		}

		return View::make('index', array(
			'request' => $request,
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'albums' => $albums,
		));
	}

	public function about()
	{
		$id = 1;
		$user = User::find($id);

		return View::make('static.about', array(
			'user' => $user
		));
	}


	public function icons() {

		$minutes = 60;
		// remember(60)->
		$icons = Cache::remember('icons', $minutes, function() {
			return Achievement::pluck('id');
		});

		return View::make('static.icons', array(
			'icons' => $icons
		));

	}

}
