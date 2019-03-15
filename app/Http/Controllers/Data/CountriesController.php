<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\User\Wanted;
use App\Models\Data\Country;

class CountriesController extends Controller {

	private $prefix = 'countries';

    public function list(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		$sort = $request->get('sort', 'name');
		$order = $request->get('order', 'asc');
		$limit = 28;

		$sort_options = array(
			'name' => 'Название',
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$wanted = array();
		$not_wanted = array();

		$elements = Country::orderBy($sort, $order)
			->paginate($limit)
		;

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
			'options' => $options,
		));

    }

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
    public function item(Request $request, $id) {

		$section = SectionsHelper::getSection('films');

		$sort = $request->get('sort', 'created_at');
		$order = $request->get('order', 'desc');
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

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				->pluck('element_id')
			;

			$elements = $country->{$section->alt_name}()->orderBy($sort, $order)
				->with(array('rates' => function($query) use($user_id, $section)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $section->type)
						;
					})
				)
				->whereNotIn($section->alt_name.'.id', $not_wanted)
				->paginate($limit)
			;

		} else {

			$elements = $country->{$section->alt_name}()->orderBy($sort, $order)
				->paginate($limit)
			;

		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'sort_list' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

        return View::make($this->prefix.'.item', array(
			'request' => $request,
			'element' => $country,
			'elements' => $elements,
			'section' => $section,
			'options' => $options
		));
    }
	
}