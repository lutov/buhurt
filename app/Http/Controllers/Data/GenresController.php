<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\Data\Section;
use App\Models\User\Wanted;
use App\Models\Data\Genre;

class GenresController extends Controller {

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function sections(Request $request) {

		return View::make('genres.index', array(
			'title' => 'Жанры',
			'subtitle' => 'Разделы',
			'request' => $request,
		));

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @return \Illuminate\Contracts\View\View
	 */
	public function list(Request $request, $section) {

		$sub_section = 'genres';
		$title = 'Жанры';
		$subtitle = SectionsHelper::getSectionName($section);
		$type = SectionsHelper::getSectionType($section);

		$sort = 'name';
		$order = 'asc';
		$limit = 28;

		$elements = Genre::where('element_type', '=', $type)
			->orderBy($sort, $order)
			->paginate($limit)
		;

		return View::make('genres.list', array(
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
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
    public function item(Request $request, $section, $id) {

		//$section = $this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = $request->get('sort', $section.'.created_at');
		$sort_direction = $request->get('sort_direction', 'desc');
		$limit = 28;

		$sort_options = array(
			$section.'.created_at' => 'Время добавления',
			$section.'.name' => 'Название',
			$section.'.alt_name' => 'Оригинальное название',
			$section.'.year' => 'Год'
		);

		$genre = Genre::find($id);
		//$element_genre = new ElementGenre;

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			$elements = $type::select($section.'.*')
				->leftJoin('elements_genres', $section.'.id', '=', 'elements_genres.element_id')
				->where('genre_id', '=', $id)
				->where('element_type', '=', $type)
				->whereNotIn($section.'.id', $not_wanted)
				->with(array('rates' => function($query) use($user_id, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->orderBy($sort, $sort_direction)
				//->toSql()
				//->remember(10)
				->paginate($limit)
			;
			//echo $elements; die();

		} else {

			$elements = $type::select($section.'.*')
				->leftJoin('elements_genres', $section.'.id', '=', 'elements_genres.element_id')
				->where('genre_id', '=', $id)
				->where('element_type', '=', $type)
				->orderBy($sort, $sort_direction)
				//->toSql()
				//->remember(10)
				->paginate($limit)
			;

		}

		if(!empty($genre)) {

			return View::make('genres.item', array(
				'request' => $request,
				'genre' => $genre,
				'elements' => $elements,
				'section' => $section,
				'ru_section' => $ru_section,
				'sort_options' => $sort_options
			));

		} else {

			return Redirect::to('/');

		}
    }
	
}