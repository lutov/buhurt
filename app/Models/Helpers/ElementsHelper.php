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
	 * @param object $element
	 * @param string $section
	 * @return string
	 */
	public static function getElement($element, string $section = '') {

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

			$elements_list .= '<div class="rating">';
			if (isset($element->rates) && 0 != count($element->rates) && Auth::check()) {

				$user_id = Auth::user()->id;
				$rate = $element
					->rates
					->where('user_id', $user_id)
					->toArray()
				;

				if(0 != count($rate)) {
					$elements_list .= '<input name="val" value="' . array_shift($rate)['rate'] . '" type="hidden">';
				}

			}
			$elements_list .= '<input type="hidden" name="vote_id" value="'.$section.'/'.$element->id.'"/>';
			$elements_list .= '</div>';

			//$elements_list .= '<div class="d-flex justify-content-between align-items-center">';

			//$elements_list .= '<div class="btn-group">';

			//$elements_list .= '<button type="button" class="btn btn-sm btn-outline-secondary">View</button>';

			//$elements_list .= '</div>';

			//$elements_list .= '<small class="text-muted">'.$user_rate.'</small>';

			//$elements_list .= '</div>';

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
	 * @param $elements
	 * @param $section
	 * @param $subsection
	 * @param array $sort_options
	 * @param bool $paginate
	 * @return string
	 */
	public static function get_list($elements, $section, $subsection, $sort_options = array(), $paginate = true) {

		$elements_list = '';
		$default_sort = 'name';

		//var_dump($elements[0]->rates[0]->rate);

		if(!empty($sort_options)) {

			$sort_direction = ElementsHelper::get_sort_direction();

			$elements_list .= Form::open(array('class' => 'sort', 'method' => 'GET'));
			$elements_list .= Form::hidden('view', Input::get('view', 'plates'));
			$elements_list .= Form::select('sort', $sort_options, Input::get('sort', $default_sort));
			$elements_list .= Form::select('sort_direction', $sort_direction, Input::get('sort_direction', 'desc'));
			$elements_list .= Form::hidden('page', Input::get('page', 1));
			$elements_list .= '&nbsp;';
			$elements_list .= Form::submit('Сортировать');
			$elements_list .= Form::close();

		}

		$elements_list .= '<ul>';

		foreach ($elements as $element) {

			if('' != $element->name) {
				$elements_list .= '<li>';
				$elements_list .= '<a href="/' . $section . '/';
				if (!empty($subsection)) {
					$elements_list .= $subsection . '/';
				}
				$elements_list .= $element->id . '">';
				$elements_list .= $element->name;
				$elements_list .= '</a>';
				$elements_list .= '</li>';
			}

		}

		$elements_list .= '</ul>';

		if ($paginate) {
			$elements_list .= $elements->appends(
				array(
					//'view' => Input::get('view', 'plates'),
					//'sort' => Input::get('sort', $default_sort),
					//'sort_direction' => Input::get('sort_direction', 'desc')
				)
			)->render();
		}

		return $elements_list;
	}

	/**
	 * @param object $elements
	 * @param string $section
	 * @param array $options
	 * @param array $sort_options
	 * @return string
	 */
	public static function getElements($elements, string $section = '', array $options = array(), array $sort_options = array()) {

		$elements_list = '';
		$default_sort = $section.'.created_at';

		if(!count($options)) {
			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
			);
		}

		/*
		$view = Input::get('view', 'plates');
		if('table' == $view) {

			$elements_list .= '<table class="elements_table">';

			foreach ($elements as $element) {
				$elements_list .= '<tr>';

				$elements_list .= '<td>';
				$elements_list .= '<a href="/' . $section . '/' . $element->id . '">';
				$elements_list .= $element->name;
				$elements_list .= '</a>';
				$elements_list .= '</td>';

				$elements_list .= '<td>';
				$elements_list .= $element->alt_name;
				$elements_list .= '</td>';

				$elements_list .= '<td>';
				$elements_list .= $element->year;
				$elements_list .= '</td>';

				$elements_list .= '<td>';
				$elements_list .= '<div class="rating">';
				if (isset($element->rates) && 0 != count($element->rates) && Auth::check())
				{
					$user_id = Auth::user()->id;
					$rate = $element
						->rates
						->where('user_id', $user_id)
						->toArray()
					;

					/*
					if(Helpers::is_admin()) {
						Config::set('app.debug', true);
						echo '<pre>'.print_r($rate, true).'</pre>';
					}
					* /
					if(0 != count($rate)) {
						$elements_list .= '<input name="val" value="' . array_shift($rate)['rate'] . '" type="hidden">';
					}
				}
				$elements_list .= '<input type="hidden" name="vote_id" value="'.$section.'/'.$element->id.'"/>';
				$elements_list .= '</div>';
				$elements_list .= '</td>';

				$elements_list .= '</tr>';
			}

			$elements_list .= '</table>';

		} else {

		}
		*/

		if($options['header']) {$elements_list .= ElementsHelper::getHeader();}

		foreach ($elements as $element) {

			$elements_list .= ElementsHelper::getElement($element, $section);

		}

		if($options['footer']) {$elements_list .= ElementsHelper::getFooter();}

		if ($options['paginate']) {

			$elements_list .= $elements->appends(
				array(
					//'view' => Input::get('view', 'plates'),
					'sort' => Input::get('sort', $default_sort),
					'sort_direction' => Input::get('sort_direction', 'desc')
				)
			)->render();

		}

		if(!empty($sort_options)) {

			$sort_direction = ElementsHelper::get_sort_direction();

			//$elements_list .= '<section class="text-center pb-3">';
			$elements_list .= Form::open(array('class' => 'sort', 'method' => 'GET'));
			$elements_list .= Form::hidden('view', Input::get('view', 'plates'));
			$elements_list .= Form::select('sort', $sort_options, Input::get('sort', $default_sort));
			$elements_list .= Form::select('sort_direction', $sort_direction, Input::get('sort_direction', 'desc'));
			$elements_list .= Form::hidden('page', Input::get('page', 1));
			$elements_list .= '&nbsp;';
			$elements_list .= Form::submit('Сортировать');
			$elements_list .= Form::close();
			//$elements_list .= '</section>';

		}

		/*
		if($switch2table) {

			$elements_list .= '<section class="text-center">';
			$elements_list .= Form::open(array('class' => 'switch2table', 'method' => 'GET')); // 'url' => Helpers::append_url_param('table_view', 'true'),
			$elements_list .= Form::hidden('view', 'table');
			$elements_list .= Form::hidden('sort', Input::get('sort', $default_sort));
			$elements_list .= Form::hidden('sort_direction', Input::get('sort_direction', 'desc'));
			$elements_list .= Form::hidden('page', Input::get('page', 1));
			$elements_list .= Form::submit('Показать таблицу');
			$elements_list .= Form::close();
			$elements_list .= '</section>';

		}
		*/

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
	 * @param $section
	 * @return string
	 */
	public static function getRecommend($section) {

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
				$result = ElementsHelper::getElement($element, $section);
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