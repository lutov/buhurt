<?php namespace App\Http\Controllers;

use App\Models\Helpers\SectionsHelper;
use App\Models\Helpers\TextHelper;
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
use App\Models\Section;
use App\Models\Helpers;
use App\Models\Wanted;

class YearsController extends Controller {

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function sections(Request $request) {

		return View::make('years.index', array(
			'title' => 'Календарь',
			'subtitle' => 'Разделы',
			'request' => $request,
		));

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @return mixed
	 */
	public function list(Request $request, string $section) {

		$sub_section = 'years';
		$title = 'Календарь';
		$subtitle = SectionsHelper::getSectionName($section);

		$sort = 'year';
		$order = 'desc';
		//$limit = $this->normal_limit;

		$elements = DB::table($section)
			//->select('year')
			->selectRaw('`year` as `id`, `year` as `name`')
			->distinct()
			->orderBy($sort, $order)
			//->remember(60)
			->get()
			//->paginate($limit)
		;

		return View::make('years.list', array(
			'request' => $request,
			'title' => $title,
			'subtitle' => $subtitle,
			'sub_section' => $sub_section,
			'section' => $section,
			'elements' => $elements
		));

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $year
	 * @return \Illuminate\Contracts\View\View
	 */
    public function item(Request $request, $section, $year) {

		//$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = Helpers::get_section_name($section);
		$type = Helpers::get_section_type($section);
		//die($type);

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

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			//die($section);
			$elements = $type::orderBy($sort, $order)
				->with(array('rates' => function($query) use($user_id, $section, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->whereNotIn($section.'.id', $not_wanted)
				->where('year', '=', $year)
				->paginate($limit)
				//->toSql()
			;
			//die($elements);
		} else {
			$elements = $type::orderBy($sort, $order)
				->where('year', '=', $year)
				->paginate($limit)
			;
		}

		if(!empty($elements)) {

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

			return View::make('years.item', array(
				'request' => $request,
				'year' => $year,
				'elements' => $elements,
				'section' => $section,
				'ru_section' => $ru_section,
				'options' => $options
			));

		} else {

			return Redirect::to('/years/');

		}

    }
	
}