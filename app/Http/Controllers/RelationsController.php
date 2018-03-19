<?php namespace App\Http\Controllers;

use Auth;
use DB;
use View;
use Input;
use Redirect;
use App\Models\Book;
use App\Models\Film;
use App\Models\Game;
use App\Models\Album;
use App\Models\Helpers;
use App\Models\Relation;
use App\Models\ElementRelation;

class RelationsController extends Controller {

    public function show_all()
    {
		/*
	    $genres = DB::table($this->prefix);
        return View::make('books.genres', array(
			'books' => $genres
		));
		*/
    }
	
    public function show_item($section, $id)
    {

		$books = $films = $games = [];
		
		$relation_list = Relation::all();
		$relation = array();
		foreach($relation_list as $key => $value) {
			$relation[$value->id] = $value->name;
		}
		
		$section_name = Helpers::get_object_by($section);
		$section_type = Helpers::get_section_type($section);
		$element = $section_name::find($id);
		
		$relations = ElementRelation::with($section)
			->with('relation')
			->where('to_id', '=', $id)
			->where('element_type', '=', $section_type)
			//->orderBy($section.'.year')
			->get()
		;

		$sort_direction = 'asc';
		$limit = 28;

		/*
		$books = Book::select('books.*')
			->leftJoin('elements_relations', 'books.id', '=', 'elements_relations.element_id')
			->where('relation_id', '=', $id)
			->where('element_type', '=', 'Book')
			->orderBy('name', $sort_direction)
			//>remember(60)
			//->get()
			->paginate($limit)
		;

		$films = Film::select('films.*')
			->leftJoin('elements_relations', 'films.id', '=', 'elements_relations.element_id')
			->where('relation_id', '=', $id)
			->where('element_type', '=', 'Film')
			->orderBy('name', $sort_direction)
			//->remember(60)
			//->get()
			->paginate($limit)
		;

		$games = Game::select('games.*')
			->leftJoin('elements_relations', 'games.id', '=', 'elements_relations.element_id')
			->where('relation_id', '=', $id)
			->where('element_type', '=', 'Game')
			->orderBy('name', $sort_direction)
			//->remember(60)
			//->get()
			->paginate($limit)
		;
		*/

		return View::make('relations.item', array(
			'element' => $element,
			'section' => $section,
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'relation' => $relation,
			'relations' => $relations
		));
    }
	
    public function add_relation($section, $id) {

		if(Helpers::is_admin()) {
			
			$books = $films = $games = [];
			
			$relation_all = Relation::all();
			$relation_list = array();
			foreach($relation_all as $key => $value) {
				$relation_list[$value->id] = $value->alt_name;
			}
			
			$section_name = Helpers::get_object_by($section);
			$section_type = Helpers::get_section_type($section);
			$element = $section_name::find($id);
			
			$relations_input = Input::get('relations');
			$relations = explode(', ', $relations_input);
			
			$relation_id = Input::get('relation');
			$relation_name = $relation_list[$relation_id];
			
			//print_r($relations);
			
			foreach($relations as $key => $value) {
				
				$this->set_relation($id, $key, $relations, $relation_id, $relation_name, $section_type, $relation_list);
				
			}
			
			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Связи установлены');
		
		} else {
			
			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Нет доступа к разделу');
			
		}
		
	}
	
	private function set_relation($id, $key, $relations, $relation_id, $relation_name, $section_type, $relation_list) {

		$value = $relations[$key];
		
		switch($relation_name) {
					
			case 'Sequel':
					
				// sequel itself
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $id;
				$element_relation->element_id = $value;
				$element_relation->relation_id = array_search('Sequel', $relation_list);						
						
				$element_relation->save();
				
				// setting prequel at the same time
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $value;
				$element_relation->element_id = $id;
				$element_relation->relation_id = array_search('Prequel', $relation_list);						
						
				$element_relation->save();

			break;
					
			case 'Prequel':
					
				// prequel itself
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $id;
				$element_relation->element_id = $value;
				$element_relation->relation_id = array_search('Prequel', $relation_list);						
						
				$element_relation->save();
					
				// setting sequel at the same time
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $value;
				$element_relation->element_id = $id;
				$element_relation->relation_id = array_search('Sequel', $relation_list);						
						
				$element_relation->save();
						
			break;
					
			default:
					
				// relation itself
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $id;
				$element_relation->element_id = $value;
				$element_relation->relation_id = $relation_id;						
						
				$element_relation->save();
					
				// reverse relation
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $value;
				$element_relation->element_id = $id;
				$element_relation->relation_id = $relation_id;						
						
				$element_relation->save();
					
			break;

		}
		
	}
	
}