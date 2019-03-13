<?php namespace App\Http\Controllers;

use App\Models\Helpers\ElementsHelper;
use App\Models\Helpers\SectionsHelper;
use App\Models\Helpers\TextHelper;
use Auth;
use DB;
use Illuminate\Http\Request;
use Input;
use View;
use Redirect;
use App\Models\Section;
use App\Models\Book;
use App\Models\Wanted;
use App\Models\ElementRelation;

class BooksController extends Controller {

	private $prefix = 'books';

    public function list(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		$sort = $request->get('sort', 'created_at');
		$order = $request->get('order', 'desc');
		$limit = 28;

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Название',
			'alt_name' => 'Оригинальное название',
			'year' => 'Год'
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$wanted = array();
		$not_wanted = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;

			$wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('wanted', '=', 1)
				->where('user_id', '=', $user_id)
				->pluck('element_id')
				->toArray()
			;

			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				->pluck('element_id')
				->toArray()
			;

			$elements = Book::where('verified', '=', 1)
				->whereNotIn('id', $not_wanted)
				->with(array('rates' => function($query)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', 'Book')
						;
					})
				)
				->orderBy($sort, $order)
				->paginate($limit)
			;
		} else {

			$elements = Book::where('verified', '=', 1)
				->orderBy($sort, $order)
				->paginate($limit)
			;
		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'wanted' => $wanted,
			'not_wanted' => $not_wanted,
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
	
    public function item(Request $request, $id) {

		$book = Book::find($id);

		if(count($book)) {

			$similar = array();

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

			$rating = ElementsHelper::countRating($book);
			
			$section_type = 'Book';
			$relations = ElementRelation::where('to_id', '=', $id)
				->where('to_type', '=', $section_type)
				->count()
			;

			$sim_options['type'] = 'Book';
			$sim_options['genres'] = $genres;
			$sim_limit = 3;

			for($i = 0; $i < $sim_limit; $i++) {
				$similar[] = ElementsHelper::getSimilar($sim_options);
			}

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'element' => $book,
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

		} else {

			return Redirect::to('/books');

		}
    }

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getJson($id) {

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

		$rating = ElementsHelper::countRating($book);

		$section_type = 'Book';
		$relations = ElementRelation::where('to_id', '=', $id)
			->where('element_type', '=', $section_type)
			->count()
		;

		$sim_options['type'] = 'Book';
		$sim_options['genres'] = $genres;
		$sim_limit = 0;

		for($i = 0; $i < $sim_limit; $i++) {
			$similar[] = ElementsHelper::getSimilar($sim_options);
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

	/**
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function transfer(int $id = 0) {

		$section = $this->prefix;
		$type = 'Book';

		$recipient_id = Input::get('recipient_id');

		$element_rate = DB::table('rates')
			->where('element_type', '=', $type)
			->where('element_id', '=', $recipient_id)
			->get()
			->toArray()
		;

		//echo Helpers\DebugHelper::dump($element_rate); die();

		if(!isset($element_rate[0]->id)) {

			DB::table('rates')
				->where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->update(array('element_id' => $recipient_id))
			;

		}

		ElementsHelper::deleteElement($id, $section, $type);

		return Redirect::to('/'.$this->prefix.'/'.$recipient_id);

	}
	
}