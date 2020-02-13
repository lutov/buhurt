<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\User\Unwanted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\Data\Section;
use App\Models\User\Wanted;
use App\Models\Data\Genre;

class GenresController extends Controller {

	private $prefix = 'genres';

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function sections(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		return View::make('genres.index', array(
			'title' => 'Жанры',
			'subtitle' => 'Разделы',
			'section' => $section,
			'request' => $request,
		));

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @return \Illuminate\Contracts\View\View
	 */
	public function list(Request $request, $section) {

		//dd($section);
		//$parent = SectionsHelper::getSection('genres');
		//$section = SectionsHelper::getSection($section);
		$genre = Genre::find($section);
		$section = SectionsHelper::getSection(SectionsHelper::getSectionBy($genre->element_type)); //dd($section);

		$sort = 'name';
		$order = 'asc';
		$limit = 28;

		$elements = Genre::where('element_type', '=', $section->type)
			->orderBy($sort, $order)
			->paginate($limit)
		;

		return View::make('genres.list', array(
			'request' => $request,
			//'parent' => $parent,
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

		$parent = SectionsHelper::getSection('genres');
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

		$genre = Genre::find($id);

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$unwanted = Unwanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			$elements = $section->type::select($section->alt_name.'.*')
				->leftJoin('elements_genres', $section->alt_name.'.id', '=', 'elements_genres.element_id')
				->where('genre_id', '=', $id)
				->where('element_type', '=', $section->type)
				->whereNotIn($section->alt_name.'.id', $unwanted)
				->with(array('rates' => function($query) use($user_id, $section)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $section->type)
						;
					})
				)
				->orderBy($sort, $order)
				//->toSql()
				->paginate($limit)
			;

		} else {

			$elements = $section->type::select($section->alt_name.'.*')
				->leftJoin('elements_genres', $section->alt_name.'.id', '=', 'elements_genres.element_id')
				->where('genre_id', '=', $id)
				->where('element_type', '=', $section->type)
				->orderBy($sort, $order)
				//->toSql()
				->paginate($limit)
			;

		}

		if(!empty($genre)) {

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/'.$parent->alt_name.'/' . $id . '.jpg';
			if (file_exists($file_path)) {
				$cover = $id;
			}

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_list' => $sort_options,
				'sort' => $sort,
				'order' => $order,
				'cover' => $cover,
			);

			return View::make('genres.item', array(
				'request' => $request,
				'cover' => $cover,
				'element' => $genre,
				'elements' => $elements,
				'parent' => $parent,
				'section' => $section,
				'options' => $options
			));

		} else {

			return Redirect::to('/');

		}
    }
	
}