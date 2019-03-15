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

	private $prefix = 'years';

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function sections(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

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

		$parent = SectionsHelper::getSection('years');
		$section = SectionsHelper::getSection($section);

		$section->setParent($parent);

		$sort = 'year';
		$order = 'desc';
		//$limit = $this->normal_limit;

		$elements = DB::table($section->alt_name)
			->selectRaw('`year` as `id`, `year` as `name`')
			->distinct()
			->orderBy($sort, $order)
			->get()
			//->paginate($limit)
		;

		return View::make('years.list', array(
			'request' => $request,
			'parent' => $parent,
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

			$model = 'App\\Models\\Data\\'.$parent->type;
			$element = new $model();
			$element->setParent($section);
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