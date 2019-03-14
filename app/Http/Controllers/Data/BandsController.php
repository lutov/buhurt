<?php namespace App\Http\Controllers\Data;

use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\Data\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Data\Band;

class BandsController extends Controller {

	private $prefix = 'bands';

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function list(Request $request) {

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

	/**
	 * @param Request $request
	 * @param $id
	 * @return mixed
	 */
    public function item(Request $request, $id) {

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

			$albums = $band->albums()->orderBy('created_at', $sort_direction)->paginate($limit);
			$members = $band->members()->orderBy('created_at', $sort_direction)->paginate($limit);

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
				'sort_options' => $sort_options
			));
		} else {
			// нет такой буквы
			return Redirect::home()->with('message', 'Нет такой персоны');
		}
    }
	
}