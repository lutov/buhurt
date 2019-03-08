<?php namespace App\Http\Controllers;

use App\Models\Helpers\TextHelper;
use App\Models\Section;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Redirect;
use App\Models\Band;

class BandsController extends Controller {

	private $prefix = 'bands';

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function show_all(Request $request) {

		$section = $this->prefix;

		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', 'created_at');
		$order = Input::get('order', 'desc');
		$limit = 28;

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Название',
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$elements = Band::orderBy($sort, $order)
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

		$section = $this->prefix;

		$band = Band::find($id);

		if(isset($band->id)) {
			$photo = 0;
			$file_path = public_path() . '/data/img/covers/bands/'.$id.'.jpg';
			if (file_exists($file_path)) {
				$photo = $id;
			}

			$sort = Input::get('sort', 'created_at');
			$sort_direction = Input::get('sort_direction', 'desc');
			$limit = 28;

			$albums = $band->albums()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$members = $band->members()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			//$screenplays = $band->screenplays()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			//$productions = $band->productions()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			//$actions = $band->actions()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)

			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'year' => 'Год'
			);

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'section' => $section,
				'element' => $band,
				'cover' => $photo,
				'albums' => $albums,
				'members' => $members,
				//'actions' => $actions,
				//'screenplays' => $screenplays,
				//'productions' => $productions,
				'sort_options' => $sort_options
			));
		} else {
			// нет такой буквы
			return Redirect::home()->with('message', 'Нет такой персоны');
		}
    }
	
    public function show_authors() {

        return View::make($this->prefix.'.authors');

    }	
	
    public function show_author() {

        return View::make($this->prefix.'.author');

    }
	
}