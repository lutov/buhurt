<?php namespace App\Http\Controllers;

use App\Models\Helpers\TextHelper;
use Auth;
use DB;
use Illuminate\Http\Request;
use View;
use Input;
use Redirect;
use App\Models\Section;
use App\Models\Wanted;
use App\Models\Country;

class CountriesController extends Controller {

	private $prefix = 'countries';

    public function list(Request $request) {

		$section = $this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', 'name');
		$order = Input::get('order', 'asc');
		$limit = 28;

		$sort_options = array(
			'name' => 'Название',
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$wanted = array();
		$not_wanted = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;

			$elements = Country::orderBy($sort, $order)
				->paginate($limit)
			;

		} else {

			$elements = Country::orderBy($sort, $order)
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
			'ru_section' => $ru_section,
			'options' => $options,
		));

    }

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
    public function item(Request $request, $id) {

		$section = 'films'; //$this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', 'created_at');
		$order = Input::get('order', 'desc');
		$limit = 28;

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Название',
			'alt_name' => 'Оригинальное название',
			'year' => 'Год'
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$country = Country::find($id);

		$wanted = array();
		$not_wanted = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			$elements = $country->$section()->orderBy($sort, $order)
				->with(array('rates' => function($query) use($user_id, $section, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->whereNotIn($section.'.id', $not_wanted)
				->paginate($limit)
			;

		} else {

			$elements = $country->$section()->orderBy($sort, $order)
				->paginate($limit)
			;

		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			//'wanted' => $wanted,
			//'not_wanted' => $not_wanted,
			'sort_list' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

        return View::make($this->prefix.'.item', array(
			'request' => $request,
			'country' => $country,
			'films' => $elements,
			'section' => $section,
			'ru_section' => $ru_section,
			'options' => $options
		));
    }
	
}