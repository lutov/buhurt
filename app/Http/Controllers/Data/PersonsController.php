<?php namespace App\Http\Controllers\Data;

use App\Helpers\RolesHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Data\Person;

class PersonsController extends Controller {

	private $prefix = 'persons';

	/**
	 * @param Request $request
	 * @return mixed
	 */
    public function list(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		$sort = $request->get('sort', 'name');
		$order = $request->get('order', 'asc');
		$limit = 28;

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Имя',
		);

		$elements = Person::orderBy($sort, $order)
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
	 * @return mixed
	 */
    public function item(Request $request, $id) {

		$section = SectionsHelper::getSection($this->prefix);

		$person = Person::find($id);

		if(isset($person->id)) {
			$photo = 0;
			$file_path = public_path() . '/data/img/covers/persons/'.$id.'.jpg';
			if (file_exists($file_path)) {
				$photo = $id;
			}
			
			$all_books = [];
			
			$writer_genres = DB::table('writers_genres')
				->where('element_type', '=', 'Book')
				->where('person_id', '=', $person->id)
				->pluck('genre_id')
			;
			
			if(!count($writer_genres)) {
				$all_books = $person
					->books()
					->pluck('books.id')
					->toArray()
				;
					
				$all_genres = DB::table('elements_genres')
					->select(DB::raw('count(genre_id) as weight, genre_id'))
					->where('element_type', '=', 'Book')
					->whereIn('element_id', $all_books)
					->groupBy('genre_id')
					->orderBy('weight', 'desc')
					->limit(3)
					->pluck('genre_id')
				;
				
				//write		
				$new_genres = DB::table('elements_genres')
					->select(DB::raw('count(genre_id) as weight, genre_id'))
					->where('element_type', '=', 'Book')
					->whereIn('element_id', $all_books)
					->groupBy('genre_id')
					->orderBy('weight', 'desc')
					->limit(3)
					->get()
				;
				$new_genres_array = [];
				foreach($new_genres as $key => $value) {
					
					$new_genres_array['element_type'] = 'Book';
					$new_genres_array['person_id'] = $person->id;
					$new_genres_array['genre_id'] = $value->genre_id;
					$new_genres_array['weight'] = $value->weight;
					DB::table('writers_genres')->insert($new_genres_array);
				}
					
			} else {
				
				$all_genres = $writer_genres;
				
			}
			
			$top_genres = DB::table('genres')
				->where('element_type', '=', 'Book')
				->whereIn('id', $all_genres)
				->get()
			;

			$sort = $request->get('sort', 'name');
			$order = $request->get('order', 'asc');
			$limit = 28;

			$sort = TextHelper::checkSort($sort);
			$order = TextHelper::checkOrder($order);

			$books = $person->books()->orderBy($sort, $order)->paginate($limit);
			$directions = $person->directions()->orderBy($sort, $order)->paginate($limit);
			$screenplays = $person->screenplays()->orderBy($sort, $order)->paginate($limit);
			$productions = $person->productions()->orderBy($sort, $order)->paginate($limit);
			$actions = $person->actions()->orderBy($sort, $order)->paginate($limit);

			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'year' => 'Год'
			);

			$comments = array();

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_list' => $sort_options,
				'sort' => $sort,
				'order' => $order,
			);

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'section' => $section,
				'element' => $person,
				'cover' => $photo,
				'books' => $books,
				'directions' => $directions,
				'actions' => $actions,
				'screenplays' => $screenplays,
				'productions' => $productions,
				'sort_options' => $sort_options,
				'all_books' => $all_books,
				'all_genres' => $all_genres,
				'top_genres' => $top_genres,
				'comments' => $comments,
				'options' => $options,
			));
		} else {
			// нет такой буквы
			return Redirect::to('/persons')->with('message', 'Нет такой персоны');
		}
    }

	/**
	 * @param Request $request
	 * @param int $id
	 * @return mixed
	 */
	public function transfer(Request $request, int $id = 0) {

		$recipient_id = $request->get('recipient_id');

		DB::table('writers_books')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('directors_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('screenwriters_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('producers_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('writers_genres')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('actors_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));

		DB::table('persons')->where('id', '=', $id)->delete();//update(array('name' => ''));

		return Redirect::to('/'.$this->prefix.'/'.$recipient_id);

	}
	
}