<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\User\Unwanted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class YearsController extends Controller {

	private $section = 'years';
	private $type = 'Year';

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function sections(Request $request) {

		$section = SectionsHelper::getSection($this->section);

		return View::make('years.index', array(
			'title' => 'Календарь',
			'subtitle' => 'Разделы',
			'request' => $request,
			'section' => $section,
		));

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @return mixed
	 */
	public function list(Request $request, string $section) {

		$section = SectionsHelper::getSection($section);

		$sort = $request->get('sort', 'year');
		$order = $request->get('order', 'desc');
		$cache = $request->get('cache', 'yes');
		$limit = 28;

		$sort_options = array(
			'year' => 'Год',
			'count' => 'Число произведений'
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		if('no' != $cache) {
			$minutes = 60;
			$var_name = 'years_'.$section->name.'_'.$sort.'_'.$order; //Cache::forget($var_name);
			$elements = Cache::remember($var_name, $minutes, function () use ($section, $sort, $order) {
				return DB::table($section->alt_name)
					->selectRaw('`year` as `id`, `year` as `name`, count(id) as `count`')
					->groupBy('year')
					->orderBy($sort, $order)
					->get()
				;
			});
		} else {
			$elements = DB::table($section->alt_name)
				->selectRaw('`year` as `id`, `year` as `name`, count(id) as `count`')
				->groupBy('year')
				->orderBy($sort, $order)
				->get()
			;
		}

		//dd($elements);

		$options = array(
			'header' => true,
			'paginate' => false,
			'count' => true,
			'columns' => array(
				'count' => 10,
				'width' => '5em'
			),
			'sort_options' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make('years.list', array(
			'request' => $request,
			'section' => $section,
			'elements' => $elements,
			'options' => $options
		));

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $year
	 * @return \Illuminate\Contracts\View\View
	 */
    public function item(Request $request, $section, $year) {

		$parent = SectionsHelper::getSection('years');
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
			$unwanted = Unwanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('user_id', '=', $user_id)
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
				->whereNotIn($section.'.id', $unwanted)
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

			$model = 'App\\Models\\Data\\'.$parent->type;
			$element = new $model();
			$element->name = $year.'-й год';

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_list' => $sort_options,
				'sort' => $sort,
				'order' => $order,
			);

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/'.$parent->alt_name.'/' . $year . '.jpg';
			if (file_exists($file_path)) {
				$cover = $year;
			}

			return View::make('years.item', array(
				'request' => $request,
				'year' => $year,
				'element' => $element,
				'elements' => $elements,
				'parent' => $parent,
				'section' => $section,
				'cover' => $cover,
				'options' => $options
			));

		} else {

			return Redirect::to('/years/');

		}

    }
	
}