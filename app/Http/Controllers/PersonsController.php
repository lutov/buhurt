<?php namespace App\Http\Controllers;

use App\Models\Helpers\RolesHelper;
use DB;
use Illuminate\Http\Request;
use View;
use Input;
use Config;
use Redirect;
use App\Models\Person;
use App\Models\Helpers;

class PersonsController extends Controller {

	private $prefix = 'persons';

    public function show_all(Request $request) {

	    $persons = DB::table($this->prefix)->paginate(27);
		$photos = array();
		$default_photo = 0;
		foreach($persons as $person)
		{
			$file_path = $_SERVER['DOCUMENT_ROOT'].'data/img/covers/'.$this->prefix.'/'.$person->id.'.jpg';
			//echo $file_path.'<br/>';
			if(file_exists($file_path))
			{
				$photos[$person->id] = $person->id;
			}
			else
			{
				$covers[$person->id] = $default_photo;
			}
		}

        return View::make($this->prefix.'.index', array(
			'persons' => $persons,
			'photos' => $photos
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
	
    public function show_item(Request $request, $id) {

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

			$sort = Input::get('sort', 'created_at');
			$sort_direction = Input::get('sort_direction', 'desc');
			$limit = 28;

			$books = $person->books()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$directions = $person->directions()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$screenplays = $person->screenplays()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$productions = $person->productions()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$actions = $person->actions()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)

			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'year' => 'Год'
			);

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'person' => $person,
				'photo' => $photo,
				'books' => $books,
				'directions' => $directions,
				'actions' => $actions,
				'screenplays' => $screenplays,
				'productions' => $productions,
				'sort_options' => $sort_options,
				'all_books' => $all_books,
				'all_genres' => $all_genres,
				'top_genres' => $top_genres,
			));
		}
		else
		{
			// нет такой буквы
			return Redirect::home()->with('message', 'Нет такой персоны');
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

		DB::table('persons')->where('id', '=', $id)->update(array('name' => ''));

		return Redirect::to('/'.$this->prefix.'/'.$recipient_id);

	}
	
}