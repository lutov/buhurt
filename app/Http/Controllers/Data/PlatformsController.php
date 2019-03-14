<?php namespace App\Http\Controllers\Data;

use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Data\Section;
use App\Models\User\Wanted;
use App\Models\Data\Platform;

class PlatformsController extends Controller {

	private $prefix = 'platforms';

	public function list(Request $request) {

		$section = $this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

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

		if(Auth::check()) {

			$user_id = Auth::user()->id;

			$elements = Platform::orderBy($sort, $order)
				->paginate($limit)
			;

		} else {

			$elements = Platform::orderBy($sort, $order)
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
	
    public function item(Request $request, $id) {

		$section = 'games';
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

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

			//$games = $platform->games()->orderBy($sort, $sort_direction)->paginate($limit);

			if(Auth::check()) {

				$user_id = Auth::user()->id;
				$not_wanted = Wanted::select('element_id')
					->where('element_type', '=', $type)
					->where('not_wanted', '=', 1)
					->where('user_id', '=', $user_id)
					//->remember(10)
					->pluck('element_id')
				;

				$elements = $platform->$section()->orderBy($sort, $order)
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

				$elements = $platform->$section()->orderBy($sort, $order)
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
				'platform' => $platform,
				'games' => $elements,
				'section' => $section,
				'ru_section' => $ru_section,
				'options' => $options
			));
		}
		else {
			return Redirect::to('/games/');
		}
    }
	
}