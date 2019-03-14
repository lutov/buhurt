<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\User\Wanted;

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

		$section = SectionsHelper::getSection($section);

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

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			//die($section);
			$elements = $section->type::orderBy($sort, $order)
				->with(array('rates' => function($query) use($user_id, $section) {
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $section->type)
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
			$elements = $section->type::orderBy($sort, $order)
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
				'options' => $options
			));

		} else {

			return Redirect::to('/years/');

		}

    }
	
}