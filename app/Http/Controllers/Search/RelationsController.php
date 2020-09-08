<?php namespace App\Http\Controllers\Search;

use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Helpers\RolesHelper;
use App\Helpers\SectionsHelper;
use App\Models\Data\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Search\Relation;
use App\Models\Search\ElementRelation;

class RelationsController extends Controller {

	protected string $section = 'relations';
	
    public function getRelations(Request $request, $section, $id) {

		$section = SectionsHelper::getSection($section);
		$element = $section->type::find($id);

		$sort = $request->get('sort', 'name');
		$order = $request->get('order', 'asc');
		$limit = 28;

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$sort_options = array();

		$relations_list = array();
		$relations = Relation::select(array('id', 'name'))->get();
		foreach($relations as $key => $value) {
			$relations_list[$value->id] = $value->name;
		}

		$sections_list = array();
		$sections = Section::select(array('name', 'alt_name'))->get();
		foreach($sections as $key => $value) {
			$sections_list[$value->alt_name] = $value->name;
		}

		$relations_simple = ElementRelation::where('to_id', '=', $id)
			->where('to_type', '=', $section->type)
			->get()
			->toArray()
		;

		$relations = ElementRelation::select('elements_relations.*', 'name as caption')
			->leftJoin('relations', 'relation_id', 'relations.id')
			->where('to_id', '=', $id)
			->where('to_type', '=', $section->type)
			//->toSql()
			->get()
		;

		//dd($relations);

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'sort_options' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make('sections.relations.item', array(
			'request' => $request,
			'element' => $element,
			'section' => $section,
			'relations_list' => $relations_list,
			'relations_simple' => $relations_simple,
			'sections_list' => $sections_list,
			'relations' => $relations,
			'options' => $options,
		));
    }

	/**
	 * @param Request $request
	 * @param $section
	 * @param $id
	 * @return mixed
	 */
    public function addRelation(Request $request, $section, $id) {

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
			
			$relations_input = $request->get('relations');
			$relations = explode(', ', $relations_input);
			
			$relation_id = $request->get('relation');
			$relation_name = $relation_list[$relation_id];

			$relation_section = $request->get('section');
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

			while(0 < count($input_data['relations'])) {

				$input_data = $this->setRelations($input_data);

			}
			
			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Связи установлены');
		
		} else {
			
			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Нет доступа к разделу');
			
		}
		
	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function editRelation(Request $request, $section, $id) {

		if(RolesHelper::isAdmin($request)) {

			$relation_id = $request->get('relation_id');
			$element_id = $request->get('element_id');
			$element_type = SectionsHelper::getSectionType($request->get('element_section'));
			$relation_type = $request->get('relation_type');

			$relation = ElementRelation::find($relation_id);
			$relation->element_id = $element_id;
			$relation->element_type = $element_type;
			$relation->relation_id = $relation_type;
			$relation->save();

			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Связь изменена');

		} else {

			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Нет доступа к разделу');

		}

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteRelation(Request $request, $section, $id) {

		if(RolesHelper::isAdmin($request)) {

			$relation_id = $request->get('relation_id');

			$relation = ElementRelation::find($relation_id)->delete();

			return Redirect::to('/'.$section.'/'.$id.'/relations')->with('message', 'Связь удалена');

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