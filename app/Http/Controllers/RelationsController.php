<?php namespace App\Http\Controllers;

use App\Models\Helpers\RolesHelper;
use App\Models\Helpers\SectionsHelper;
use Auth;
use DB;
use Illuminate\Http\Request;
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
	
    public function show_item(Request $request, $section, $id) {

		$books = $films = $games = [];
		
		$relation_list = Relation::all();
		$relation = array();
		foreach($relation_list as $key => $value) {
			$relation[$value->id] = $value->name;
		}
		
		$section_name = SectionsHelper::getObjectBy($section);
		$section_type = SectionsHelper::getSectionType($section);
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

		return View::make('relations.item', array(
			'request' => $request,
			'element' => $element,
			'section' => $section,
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'relation' => $relation,
			'relations' => $relations
		));
    }
	
    public function add_relation(Request $request, $section, $id) {

		if(RolesHelper::isAdmin($request)) {
			
			$books = $films = $games = [];
			
			$relation_all = Relation::all();
			$relation_list = array();
			foreach($relation_all as $key => $value) {
				$relation_list[$value->id] = $value->alt_name;
			}
			
			$section_name = SectionsHelper::getObjectBy($section);
			$section_type = SectionsHelper::getSectionType($section);
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