<?php namespace App\Http\Controllers;

use App\Models\Helpers\RolesHelper;
use App\Models\Helpers\TextHelper;
use App\Models\Section;
use DB;
use Illuminate\Http\Request;
use View;
use Input;
use Config;
use Redirect;
use App\Models\Person;

class PersonsController extends Controller {

	private $prefix = 'persons';

    public function show_all(Request $request) {

		$section = $this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', 'name');
		$order = Input::get('order', 'asc');
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
			'ru_section' => $ru_section,
			'options' => $options,
		));

    }
	
    public function show_collections() {
        return View::make($this->prefix.'.collections');
    }
	
    public function show_collection() {
        return View::make($this->prefix.'.collection');
    }
	
    public function show_item(Request $request, $id) {

    	$section = 'persons';

		$person = Person::find($id);

		if(isset($person->id)) {
			$photo = 0;
			$file_path = public_path() . '/data/img/covers/persons/'.$id.'.jpg';
			if (file_exists($file_path)) {
				$photo = $id;
			}
			
			$all_books = [];
			$all_genres = [];
			$top_genres = [];
			
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
					//->get()
					->pluck('genre_id')
					//->toArray()
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
					//->pluck('genre_id')
					//->toArray()
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
				//->pluck('genre_id')
				//->toArray()
			;
			
			if(RolesHelper::isAdmin($request)) {
				//Config::set('app.debug', true);
				
			}			

			$sort = Input::get('sort', 'name');
			$order = Input::get('order', 'asc');
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
	
    public function show_authors() {

        return View::make($this->prefix.'.authors');
    }	
	
    public function show_author()
    {
        return View::make($this->prefix.'.author');
    }

	/**
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function transfer(int $id = 0) {

		$recipient_id = Input::get('recipient_id');

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