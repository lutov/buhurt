<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use DB;
use Auth;
use Form;
use Illuminate\Http\Request;
use Input;
use App\Models\Rate;

class ElementsHelper {

	/**
	 * @param $param
	 * @param $value
	 * @return string
	 */
	public static function append_url_param($param, $value) {

		$params = $_SERVER['QUERY_STRING'];

		if(!empty($params)) {$params .= '&';}
		$params .= $param.'='.$value;

		return '?'.$params;

	}

	/**
	 * @return array
	 */
	public static function get_sort_direction() {

		$sort_direction = array(
			'asc' => 'А→Я',
			'desc' => 'Я→А'
		);

		return $sort_direction;

	}

	/**
	 * @return string
	 */
	public static function getHeader() {

		$elements_list = '';

		$elements_list .= '<div class="album">';
		$elements_list .= '<div class="container">';
		$elements_list .= '<div class="row">';

		return $elements_list;

	}

	/**
	 * @param Request $request
	 * @param $element
	 * @param string $section
	 * @return string
	 */
	public static function getElement(Request $request, $element, string $section = '') {

		$elements_list = '';

		$default_cover = 0;

		if(is_object($element)) {

			$file_path = public_path() . '/data/img/covers/' . $section . '/' . $element->id . '.jpg';

			if (file_exists($file_path)) {
				$element_cover = $element->id;
			} else {
				$element_cover = $default_cover;
			}

			$link = '/' . $section . '/' . $element->id;

			$elements_list .= '<div class="col-md-3">';
			$elements_list .= '<div class="card mb-4 box-shadow">';

			$elements_list .= '<a href="'.$link.'">';

			$elements_list .= '<img class="card-img-top" src="/data/img/covers/' . $section . '/' . $element_cover . '.jpg" alt="' . $element->name . ' (' . $element->year . ')" />';

			$elements_list .= '</a>';

			$elements_list .= '<div class="card-body">';

			$elements_list .= '<p class="card-text">';

			$elements_list .= '<a href="'.$link.'">';
			$elements_list .= $element->name;
			$elements_list .= '</a>';

			$elements_list .= '</p>';

				/*
				<p id="element_edit_button">
                    <a href="/admin/delete/books/{!! $book->id !!}" onclick="return window.confirm('Удалить книгу?');">
                        <img src="/data/img/design/delete2.svg" alt="Удалить" />
                    </a>
                </p>
				<p id="element_edit_button"><a href="/admin/edit/books/{!! $book->id !!}"><img src="/data/img/design/edit.svg" alt="Редактировать" /></a></p>
				*/

				/*

				<span id="like" title="Хочу"
							@if(0 == $wanted) class="like" onclick="like('{!! $section !!}', '{!! $book->id !!}')" @else class="liked" onclick="unlike('{!! $section !!}', '{!! $book->id !!}')" @endif
									></span>
							<span id="dislike" title="Не хочу"
							@if(0 == $not_wanted) class="dislike"  onclick="dislike('{!! $section !!}', '{!! $book->id !!}')" @else class="disliked" onclick="undislike('{!! $section !!}', '{!! $book->id !!}')" @endif
					></span>

				 * */


			if(Auth::check()) {

				$elements_list .= '<div class="d-flex justify-content-between align-items-center pb-3">';
				$elements_list .= '<div class="btn-group">';

				if(RolesHelper::isAdmin($request)) {

					$elements_list .= '<a role="button" class="btn btn-sm btn-outline-success" href="/admin/edit/'.$section.'/'.$element->id.'" title="Редактировать">';
					$elements_list .= '&#9998;';
					$elements_list .= '</a>';

				}

				$elements_list .= '<button type="button" class="btn btn-sm btn-outline-success" title="Хочу">';
				$elements_list .= '&#10084;';
				$elements_list .= '</button>';

				$elements_list .= '<button type="button" class="btn btn-sm btn-outline-danger" title="Не хочу">';
				$elements_list .= '&#9785;';
				$elements_list .= '</button>';

				if(RolesHelper::isAdmin($request)) {

					$elements_list .= '<a role="button" class="btn btn-sm btn-outline-danger" href="/admin/delete/'.$section.'/'.$element->id.'" onclick="return window.confirm(\'Удалить?\');" title="Удалить">';
					$elements_list .= '&#10006;';
					$elements_list .= '</a>';

				}

				$elements_list .= '</div>';
				//$elements_list .= '<small class="text-muted"></small>';
				$elements_list .= '</div>';

				$elements_list .= '<div class="fast_rating_block">';
				if (isset($element->rates) && 0 != count($element->rates)) {

					$user_id = Auth::user()->id;
					$rate = $element
						->rates
						->where('user_id', $user_id)
						->toArray();

					if (0 != count($rate)) {

						$elements_list .= '<input name="val" value="' . array_shift($rate)['rate'] . '" class="fast_rating" type="text" autocomplete="off">';

					} else {

						$elements_list .= '<input name="val" value="0" class="fast_rating" type="text" autocomplete="off">';

					}
				} else {

					$elements_list .= '<input name="val" value="0" class="fast_rating" type="text" autocomplete="off">';

				}
				//$elements_list .= '<input type="hidden" name="vote_id" value="'.$section.'/'.$element->id.'"/>';
				$elements_list .= '</div>';

			} else {



			}

			$elements_list .= '</div>';

			$elements_list .= '</div>';
			$elements_list .= '</div>';

		}

		return $elements_list;

	}

	public static function getFooter() {

		$elements_list = '';

		$elements_list .= '</div>';
		$elements_list .= '</div>';
		$elements_list .= '</div>';

		return $elements_list;

	}

	/**
	 * @param Request $request
	 * @param $elements
	 * @param string $section
	 * @param array $options
	 * @return string
	 */
	public static function getElements(Request $request, $elements, string $section = '', array $options = array()) {

		$elements_list = '';

		if(!count($options)) {
			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
			);
		}

		if($options['header']) {$elements_list .= ElementsHelper::getHeader();}

		foreach ($elements as $element) {

			$elements_list .= ElementsHelper::getElement($request, $element, $section);

		}

		if($options['footer']) {$elements_list .= ElementsHelper::getFooter();}

		if ($options['paginate']) {

			$elements_list .= $elements->render();

		}



		return $elements_list;
	}

	/**
	 * @param $element
	 * @return array
	 */
	public static function count_rating($element) {

		$rates = $element->rates;
		$rates_count = $rates->count('rate');
		$rates_sum = $rates->sum('rate');

		$rating = array();

		if(0 != $rates_count) {
			$rating['average'] = round($rates_sum / $rates_count, 2);
			$rating['count'] = $rates_count;
		} else {
			$rating['average'] = 0;
			$rating['count'] = 0;
		}

		return $rating;

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @return string
	 */
	public static function getRecommend(Request $request, $section) {

		$result = '';

		$type = SectionsHelper::get_section_type($section);
		//die($type);

		$rows = Rate::where('element_type', '=', $type)
			->where('user_id', '=', 1)
			->where('rate', '>', 6)
			->get()
		;

		$rows_count = $rows->count();

		$rand_row = rand(0, $rows_count);

		if(isset($rows[$rand_row]) && !empty($rows[$rand_row])) {
			$element_id = $rows[$rand_row]->element_id;

			//$type = 'App\Models\\'.$type;
			$obj_of_type = new $type;
			//die(print_r($obj_of_type));
			$element = $obj_of_type->find($element_id);

			if (!empty($element)) {
				$result = ElementsHelper::getElement($request, $element, $section);
			}
		}

		return $result;

	}

	/**
	 * @param array $options
	 * @return array
	 */
	public static function get_similar($options = array()) {

		$sim_elem = [];

		if(count($options)) {

			$rand_id = DB::table('elements_genres')
				->orderBy(DB::raw('RAND()'))
				->where('element_type', '=', $options['type'])
				->where(function ($query) use ($options) {

					foreach($options['genres'] as $key => $value) {
						$query->orWhere('genre_id', '=', $value->genre_id);
					}

				})
				->value('element_id')
			;

			//die(gettype($rand_id));
			//die($rand_id);

			$sim_elem = $options['type']::find($rand_id);
		}

		return $sim_elem;

	}

}