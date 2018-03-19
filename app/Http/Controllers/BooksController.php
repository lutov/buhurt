<?php namespace App\Http\Controllers;

use Auth;
use Input;
use View;
use Redirect;
use App\Models\Section;
use App\Models\Book;
use App\Models\Helpers;
use App\Models\Wanted;
use App\Models\ElementRelation;

class BooksController extends Controller {

	private $prefix = 'books';

    public function show_all() {

		$section = $this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', $section.'.created_at');
		$sort_direction = Input::get('sort_direction', 'desc');
		$limit = 28;

		$sort_options = array(
			$section.'.created_at' => 'Время добавления',
			$section.'.name' => 'Название',
			$section.'.alt_name' => 'Оригинальное название',
			$section.'.year' => 'Год'
		);

	    //$books = Book::orderBy($sort, $sort_direction)->paginate($limit);

		if(Auth::check())
		{
			$user_id = Auth::user()->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			$elements = Book::orderBy($sort, $sort_direction)
				->with(array('rates' => function($query)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', 'Book')
						;
					})
				)
				->whereNotIn('id', $not_wanted)
				->paginate($limit)
			;
		}
		else
		{
			$elements = Book::orderBy($sort, $sort_direction)
				->paginate($limit)
			;
		}

        return View::make($this->prefix.'.index', array(
			'elements' => $elements,
			'section' => $section,
			'ru_section' => $ru_section,
			'sort_options' => $sort_options
		));
    }
	
    public function show_collections()
    {
        return View::make($this->prefix.'.collections');
    }
	
    public function show_collection()
    {
        return View::make($this->prefix.'.collection');
    }
	
    public function show_item($id) {

		$book = Book::find($id);
		if(count($book)) {
			$writers = $book->writers;
			$publishers = $book->publishers;
			$genres = $book->genres; $genres = $genres->sortBy('name');
			$collections = $book->collections;

			//if(isset($genres[0])) {$genres = $genres[0]->genre()->get();}

			//die('<pre>'.print_r($genres, true).'</pre>');

			if(Auth::check()) {

				$user = Auth::user();
				$user_options = $user
					->options()
					->where('enabled', '=', 1)
					->pluck('option_id')
					->toArray();

				$is_other_private = in_array(2, $user_options);

				if($is_other_private) {

					$comments = $book->comments()
						->with('user')
						->where('user_id', '=', $user->id)
						->orderBy('created_at', 'desc')
						->get();

				} else {

					$comments = $book->comments()
						->with('user')
						->orderBy('created_at', 'desc')
						->get();

				}

			} else {

				$comments = $book->comments()
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
				$rate = $book->rates()->where('user_id', '=', $user_id)->first();
				if (isset($rate->rate)) {
					$user_rate = $rate->rate;
				}

				$wanted_book = $book
					->wanted()
					->where('user_id', '=', $user_id)
					->first();
				if (isset($wanted_book->id)) {
					$wanted = $wanted_book->wanted;
					$not_wanted = $wanted_book->not_wanted;
				}
			}

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/books/' . $id . '.jpg';
			if (file_exists($file_path)) {
				$cover = $id;
			}

			$section = $this->prefix;

			$rating = Helpers::count_rating($book);
			
			$section_type = 'Book';
			$relations = ElementRelation::where('to_id', '=', $id)
				->where('element_type', '=', $section_type)
				->count()
			;

			$sim_options['type'] = 'Book';
			$sim_options['genres'] = $genres;
			$sim_limit = 3;

			for($i = 0; $i < $sim_limit; $i++) {
				$similar[] = Helpers::get_similar($sim_options);
			}

			return View::make($this->prefix . '.item', array(
				'book' => $book,
				'writers' => $writers,
				'publishers' => $publishers,
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
		}
		else {
			return Redirect::to('/base/books');
		}
    }

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function get_json($id) {

		$user_rate = 0;
		$wanted = 0;
		$not_wanted = 0;
		$cover = 0;
		$similar = array();

		$book = Book::find($id);

		$writers = $book->writers;
		$publishers = $book->publishers;
		$genres = $book->genres; $genres = $genres->sortBy('name');
		$collections = $book->collections;

		$file_path = public_path() . '/data/img/covers/books/' . $id . '.jpg';
		if (file_exists($file_path)) {
			$cover = $id;
		}

		$section = $this->prefix;

		$rating = Helpers::count_rating($book);

		$section_type = 'Book';
		$relations = ElementRelation::where('to_id', '=', $id)
			->where('element_type', '=', $section_type)
			->count()
		;

		$sim_options['type'] = 'Book';
		$sim_options['genres'] = $genres;
		$sim_limit = 0;

		for($i = 0; $i < $sim_limit; $i++) {
			$similar[] = Helpers::get_similar($sim_options);
		}

		return View::make($this->prefix . '.json', array(
			'book' => $book,
			'writers' => $writers,
			'publishers' => $publishers,
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
	
    public function show_authors()
    {
        return View::make($this->prefix.'.authors');
    }	
	
    public function show_author()
    {
        return View::make($this->prefix.'.author');
    }
	
}