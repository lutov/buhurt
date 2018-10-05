<?php namespace App\Http\Controllers;

use App\Models\Helpers\RolesHelper;
use App\Models\Helpers\SectionsHelper;
use App\Models\Section;
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

    public function show_all() {
		/*
	    $genres = DB::table($this->prefix);
        return View::make('books.genres', array(
			'books' => $genres
		));
		*/
    }
	
    public function show_item(Request $request, $section, $id) {

		$books = $films = $games = [];
		
		$relations = Relation::all();
		$relation_list = array();
		foreach($relations as $key => $value) {
			$relation_list[$value->id] = $value->name;
		}

		$sections = Section::all();
		$section_list = array();
		foreach($sections as $key => $value) {
			$section_list[$value->alt_name] = $value->name;
		}
		
		$section_name = SectionsHelper::getObjectBy($section);
		$section_type = SectionsHelper::getSectionType($section);
		$element = $section_name::find($id);
		
		$relations = ElementRelation::with('relation')
			->where('to_id', '=', $id)
			//->where('element_type', '=', $section_type)
			->where('to_type', '=', $section_type)
			//->orderBy($section.'.year')
			//->toSql()
			->get()
		;

		//echo $relations;

		$sort_direction = 'asc';
		$limit = 28;

		return View::make('relations.item', array(
			'request' => $request,
			'element' => $element,
			'section' => $section,
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'relation_list' => $relation_list,
			'section_list' => $section_list,
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

			$relation_section = Input::get('section');
			$relation_type = SectionsHelper::getSectionType($relation_section);
			
			//print_r($relations);
			
			$input_data = array(
				'element' => array(
					'id' => $id,
					'type' => $section_type,
				),
				'relation' => array(
					'id' => $relation_id,
					'name' => $relation_name,
					'type' => $relation_type,
					'list' => $relation_list,
				),
				'relations' => $relations,
			);

			while(1 < count($input_data['relations'])) {

				$input_data = $this->setRelations($input_data);

			}
			
			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Связи установлены');
		
		} else {
			
			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Нет доступа к разделу');
			
		}
		
	}

	/**
	 * @param int $id
	 * @param int $key
	 * @param string $section_type
	 * @param array $relations
	 * @param int $relation_id
	 * @param string $relation_name
	 * @param string $relation_type
	 * @param array $relation_list
	 */
	private function setRelation(
		int $id = 0,
		int $key = 0,
		string $section_type = '',
		array $relations = array(),
		int $relation_id = 0,
		string $relation_name = '',
		string $relation_type = '',
		array $relation_list = array()
	) {

		$value = $relations[$key];
		
		switch($relation_name) {
					
			case 'Sequel':
					
				// sequel itself
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $relation_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $id;
				$element_relation->element_id = $value;
				$element_relation->relation_id = array_search('Sequel', $relation_list);						
						
				$element_relation->save();
				
				// setting prequel at the same time
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $relation_type;
						
				$element_relation->to_id = $value;
				$element_relation->element_id = $id;
				$element_relation->relation_id = array_search('Prequel', $relation_list);						
						
				$element_relation->save();

			break;
					
			case 'Prequel':
					
				// prequel itself
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $relation_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $id;
				$element_relation->element_id = $value;
				$element_relation->relation_id = array_search('Prequel', $relation_list);						
						
				$element_relation->save();
					
				// setting sequel at the same time
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $relation_type;
						
				$element_relation->to_id = $value;
				$element_relation->element_id = $id;
				$element_relation->relation_id = array_search('Sequel', $relation_list);						
						
				$element_relation->save();
						
			break;
					
			default:
					
				// relation itself
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $relation_type;
				$element_relation->to_type = $section_type;
						
				$element_relation->to_id = $id;
				$element_relation->element_id = $value;
				$element_relation->relation_id = $relation_id;						
						
				$element_relation->save();
					
				// reverse relation
				$element_relation = new ElementRelation();
					
				$element_relation->element_type = $section_type;
				$element_relation->to_type = $relation_type;
						
				$element_relation->to_id = $value;
				$element_relation->element_id = $id;
				$element_relation->relation_id = $relation_id;						
						
				$element_relation->save();
					
			break;

		}
		
	}

	/**
	 * @param array $input_data
	 * @return array
	 */
	private function setRelations(array $input_data = array()) {

		$id = $input_data['element']['id'];
		$type = $input_data['element']['type'];

		$relation_id = $input_data['relation']['id'];
		$relation_name = $input_data['relation']['name'];
		$relation_type = $input_data['relation']['type'];
		$relation_list = $input_data['relation']['list'];

		$relations = $input_data['relations'];

		foreach($relations as $key => $value) {

			$this->setRelation($id, $key, $type, $relations, $relation_id, $relation_name, $relation_type, $relation_list);

		}

		$output_data = $input_data;
		$output_data['element']['id'] = $relations[0];
		unset($relations[0]);
		$output_data['relations'] = array_values($relations);

		return $output_data;

	}
	
}