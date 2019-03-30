<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\User\Unwanted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\User\Wanted;
use App\Models\Data\Platform;

class PlatformsController extends Controller {

	private $prefix = 'platforms';

	/**
	 * @param Request $request
	 * @return mixed
	 */
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

		$elements = Platform::orderBy($sort, $order)
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
			'options' => $options,
		));

	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return mixed
	 */
    public function item(Request $request, $id) {

		$section = SectionsHelper::getSection('games');

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

		$platform = Platform::find($id);

		if(count($platform)) {

			if(Auth::check()) {

				$user_id = Auth::user()->id;
				$unwanted = Unwanted::select('element_id')
					->where('element_type', '=', $section->type)
					->where('user_id', '=', $user_id)
					->pluck('element_id')
				;

				$elements = $platform->{$section->alt_name}()->orderBy($sort, $order)
					->with(array('rates' => function($query) use($user_id, $section)
						{
							$query
								->where('user_id', '=', $user_id)
								->where('element_type', '=', $section->type)
							;
						})
					)
					->whereNotIn($section->alt_name.'.id', $unwanted)
					->paginate($limit)
				;

			} else {

				$elements = $platform->{$section->alt_name}()->orderBy($sort, $order)
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
				'element' => $platform,
				'elements' => $elements,
				'section' => $section,
				'options' => $options
			));
		}
		else {

			return Redirect::to('/games/');

		}
    }
	
}