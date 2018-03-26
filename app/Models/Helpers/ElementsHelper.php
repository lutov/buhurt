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
		//$elements_list .= '<div class="container">';
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
		//$elements_list .= '</div>';
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
	public static function countRating($element) {

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
	public static function getSimilar($options = array()) {

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


	/**
	 * @param Request $request
	 * @param string $section
	 * @param $element
	 * @param array $info
	 * @return string
	 */
	public static function getCardHeader(Request $request, string $section = '', $element, array $info = array()) {

		$element_title = '';

		$element_title .= '<div class="row mt-5">';
			$element_title .= '<div class="col-md-12">';

				if(isset($info['writers'])) {

					$element_title .= '<div class="h2">';
					$element_title .= DatatypeHelper::arrayToString($info['writers'], ', ', '/persons/', false, 'author');
					$element_title .= '</div>';

				}

				$element_title .= '<h1 itemprop="name">'.$element->name.'</h1>';

				if(!empty($element->alt_name)) {

					$element_title .= '<div class="h3" itemprop="alternativeHeadline">'.$element->alt_name.'</div>';

				}

				$element_title .= '<div class="btn-group mt-3">';

					if(RolesHelper::isAdmin($request)) {

						$class = 'btn btn-sm btn-outline-success';
						$href = '/admin/edit/'.$section.'/'.$element->id;
						$element_title .= '<a role="button" class="'.$class.'" href="'.$href.'" title="Редактировать">';
						$element_title .= '&#9998;';
						$element_title .= '</a>';

					}

					$class = 'btn btn-sm btn-outline-success';
					$handler = 'onclick="like(\''.$section.'\', \''.$element->id.'\')"';
					$element_title .= '<button type="button" class="'.$class.'" '.$handler.' title="Хочу">';
					$element_title .= '&#10084;';
					$element_title .= '</button>';

					$class = 'btn btn-sm btn-outline-danger';
					$handler = 'onclick="dislike(\''.$section.'\', \''.$element->id.'\')"';
					$element_title .= '<button type="button" class="'.$class.'" '.$handler.' title="Не хочу">';
					$element_title .= '&#9785;';
					$element_title .= '</button>';

					if(RolesHelper::isAdmin($request)) {

						$class = 'btn btn-sm btn-outline-danger';
						$href = '/admin/delete/'.$section.'/'.$element->id;
						$handler = 'onclick="return window.confirm(\'Удалить?\');"';
						$element_title .= '<a role="button" class="'.$class.'" href="'.$href.'" '.$handler.' title="Удалить"">';
						$element_title .= '&#10006;';
						$element_title .= '</a>';

					}

				$element_title .= '</div>';

			$element_title .= '</div>';
		$element_title .= '</div>';

		$element_title .= '<div class="row mt-3">';
			$element_title .= '<div class="col-md-12">';

			if(Auth::check()) {

				$element_title .= '<div><input class="main_rating" name="val" value="'.$info['rate'].'" type="text"></div>';

			} else {

				$element_title .= DummyHelper::regToRate();

			}

			if(!empty($rating)) {

				$element_title .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
				$element_title .= 'Средняя оценка: <b itemprop="ratingValue">'.$rating['average'].'</b>';
				$element_title .= TextHelper::number($rating['count'], array('голос', 'голоса', 'голосов'));
				$element_title .= '</div>';

			}

			$element_title .= '</div>';
		$element_title .= '</div>';

		$element_title .= '<div class="row mt-5">';
			$element_title .= '<div class="col-md-12">';

				if(isset($info['publishers']) && count($info['publishers'])) {

					$element_title .= DatatypeHelper::arrayToString($info['publishers'], ', ', '/companies/', false, 'publisher');
					$element_title .= ', ';

				}

				if(!empty($element->year)) {

					$element_title .= '<a itemprop="datePublished" href="/years/'.$section.'/'.$element->year.'">'.$element->year.'</a>';
					$element_title .= '. ';

				}

				if(count($info['genres'])) {

					$element_title .= DatatypeHelper::collectionToString(
						$info['genres'],
						'genre',
						', ',
						'/genres/'.$section.'/',
						false,
						'genre'
					);

				}

			$element_title .= '</div>';
		$element_title .= '</div>';

		return $element_title;

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param $element
	 * @param array $info
	 * @return string
	 */
	public static function getCardBody(Request $request, string $section = '', $element, array $info = array()) {

		$element_body = '';

		$element_body .= '<div class="row mt-3">';

			$element_body .= '<div class="col-md-3">';

				$element_body .= '<img itemprop="image" src="/data/img/covers/'.$section.'/'.$info['cover'].'.jpg" alt="'.$element->name.'" class="img-fluid" />';

			$element_body .= '</div>';

			$element_body .= '<div itemprop="description" class="col-md-9">';

				$element_body .= '<p>'.nl2br($element->description).'</p>';

			$element_body .= '</div>';

		$element_body .= '</div>';

		return $element_body;

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param $element
	 * @param array $info
	 * @return string
	 */
	public static function getCardFooter(Request $request, string $section = '', $element, array $info = array()) {

		$options = array(
			'header' => true,
			'paginate' => false,
			'footer' => true,
		);

		$element_footer = '';

		$element_footer .= '<div class="row mt-3">';
			$element_footer .= '<div class="col-md-12">';

			if(count($info['collections'])) {
				$element_footer .= '<p>';
				$element_footer .= 'Коллекции: ';
				$element_footer .= DatatypeHelper::collectionToString(
					$info['collections'],
					'collection',
					', ',
					'/collections/',
					false,
					"isPartOf"
				);
				$element_footer .= '</p>';
			}

			if(0 < $info['relations']) {
				$element_footer .= '<p>';
					$element_footer .= '<a href="relations/">';
						$element_footer .= 'Связи ';
						$element_footer .= '('.$info['relations'].')';
					$element_footer .= '</a>';
				$element_footer .= '</p>';
			}

			$element_footer .= '</div>';
		$element_footer .= '</div>';

		if(RolesHelper::isAdmin($request)) {

			$element_footer .= '<p>';
				$element_footer .= '<a href="relations/">Установить связи</a>';
			$element_footer .= '</p>';

		}

        if(count($info['similar'])) {

			$element_footer .= '<h3>Похожие</h3>';
			$element_footer .= ElementsHelper::getElements($request, $info['similar'], $section, $options);

        }

		return $element_footer;

	}

	/**
	 * @param $comments
	 * @return string
	 */
	public static function getCardComments($comments) {

		$element_comments = '';

		$element_comments .= '<h3>Комментарии</h3>';

		$element_comments .= '<div class="row mt-3">';

			$element_comments .= '<div class="col-md-12">';

				$element_comments .= CommentsHelper::showCommentForm();

				$element_comments .= '<div itemscope itemtype="http://schema.org/UserComments" class="comments">';

				$element_comments .= CommentsHelper::showComments($comments);

				$element_comments .= '</div>';

			$element_comments .= '</div>';

		$element_comments .= '</div>';

		return $element_comments;

	}

	/**
	 * @return string
	 */
	public static function getCardScripts() {

		$element_scripts = '';

		if(Auth::check()) {

			$element_scripts = '<script type="text/javascript" src="/data/js/card.js"></script>';

		}

		return $element_scripts;

	}

}