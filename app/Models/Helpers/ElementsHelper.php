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
	 * @param array $options
	 * @return string
	 */
	public static function getElement(Request $request, $element, string $section = '', array $options = array()) {

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

			$elements_list .= '<div class="card-img-box">';

			$elements_list .= '<a href="'.$link.'">';

			$elements_list .= '<img class="card-img-top" src="/data/img/covers/' . $section . '/' . $element_cover . '.jpg" alt="' . $element->name . ' (' . $element->year . ')" />';

			$elements_list .= '</a>';

			$elements_list .= '</div>';

			$elements_list .= '<div class="card-header">';

			$elements_list .= '<a href="'.$link.'">';
			$elements_list .= $element->name;
			$elements_list .= '</a>';

  			$elements_list .= '</div>';

			if(Auth::check()) {

				$elements_list .= '<div class="card-body text-center d-none d-xl-block">';

				//$elements_list .= '<p class="card-text">';
				//$elements_list .= '</p>';

				$elements_list .= '<div class="fast_rating_block">';
				if (isset($element->rates) && 0 != count($element->rates)) {

					$user_id = Auth::user()->id;
					$rate = $element
						->rates
						->where('user_id', $user_id)
						->toArray();

					if (0 != count($rate)) {

						$elements_list .= '<input name="val" value="' . array_shift($rate)['rate'] . '" class="fast_rating" id="rating_'.$section.'_'.$element->id.'" type="text" autocomplete="off">';

					} else {

						$elements_list .= '<input name="val" value="0" class="fast_rating" id="rating_'.$section.'_'.$element->id.'" type="text" autocomplete="off">';

					}
				} else {

					$elements_list .= '<input name="val" value="0" class="fast_rating" id="rating_'.$section.'_'.$element->id.'" type="text" autocomplete="off">';

				}

				$elements_list .= '</div>';

				if((RolesHelper::isAdmin($request)) || (isset($options['wanted']) || isset($options['not_wanted']))) {

					$elements_list .= '<div class="mt-3">';
					$elements_list .= '<div class="btn-group">';

					if (RolesHelper::isAdmin($request)) {

						$elements_list .= '<a role="button" class="btn btn-sm btn-outline-success" href="/admin/edit/' . $section . '/' . $element->id . '" title="Редактировать">';
						$elements_list .= '&#9998;';
						$elements_list .= '</a>';

					}

					if (isset($options['wanted'])) {
						if (in_array($element->id, $options['wanted'])) {
							$class = 'btn btn-sm btn-success';
							$handler = 'onclick="unlike(\'' . $section . '\', \'' . $element->id . '\')"';
						} else {
							$class = 'btn btn-sm btn-outline-success';
							$handler = 'onclick="like(\'' . $section . '\', \'' . $element->id . '\')"';
						}
						$elements_list .= '<button type="button" class="' . $class . '" ' . $handler . ' id="want_' . $element->id . '" title="Хочу">';
						$elements_list .= '&#10084;';
						$elements_list .= '</button>';
					}

					if (isset($options['not_wanted'])) {
						if (in_array($element->id, $options['not_wanted'])) {
							$class = 'btn btn-sm btn-danger';
							$handler = 'onclick="undislike(\'' . $section . '\', \'' . $element->id . '\')"';
						} else {
							$class = 'btn btn-sm btn-outline-danger';
							$handler = 'onclick="dislike(\'' . $section . '\', \'' . $element->id . '\')"';
						}
						$elements_list .= '<button type="button" class="' . $class . '" ' . $handler . ' id="not_want_' . $element->id . '" title="Не хочу">';
						$elements_list .= '&#9785;';
						$elements_list .= '</button>';
					}

					if (RolesHelper::isAdmin($request)) {

						$elements_list .= '<a role="button" class="btn btn-sm btn-outline-danger" href="/admin/delete/' . $section . '/' . $element->id . '" onclick="return window.confirm(\'Удалить?\');" title="Удалить">';
						$elements_list .= '&#10006;';
						$elements_list .= '</a>';

					}

					$elements_list .= '</div>';
					//$elements_list .= '<small class="text-muted"></small>';
					$elements_list .= '</div>';

					$elements_list .= '<script>';

					$elements_list .= '$(\'#rating_' . $section . '_' . $element->id . '\').on(\'rating:change\', function(event, value, caption) {';

					$elements_list .= 'var path = \'/rates/rate/' . $section . '/' . $element->id . '\';';
					$elements_list .= 'var params = {rate_val: value};';

					$elements_list .= '$.post(path, params, function(data) {show_popup(data);}, \'json\');';
					$elements_list .= '$.post(\'/achievements\', {}, function(data) {show_popup(data);}, \'json\');';

					$elements_list .= '});';

					$elements_list .= '</script>';

				}

				$elements_list .= '</div>';

			} else {



			}

			if(isset($options['add_text'])) {

				$elements_list .= '<div class="card-footer text-muted">';
				$elements_list .= $options['add_text'];
				$elements_list .= '</div>';

			}

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

			$elements_list .= ElementsHelper::getElement($request, $element, $section, $options);

		}

		if($options['footer']) {$elements_list .= ElementsHelper::getFooter();}

		if ($options['paginate']) {

			$elements_list .= $elements->render();

		}

		return $elements_list;

	}

	/**
	 * @param Request $request
	 * @param $elements
	 * @param string $section
	 * @param string $subsection
	 * @param array $options
	 * @return string
	 */
	public static function getList(Request $request, $elements, string $section = '', string $subsection = '', array $options = array()) {

		$elements_list = '';

		if(!count($options)) {
			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
			);
		}

		$elements_list .= '<ul class="list-unstyled">';

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

		$type = SectionsHelper::getSectionType($section);
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

				if(isset($info['bands']) && count($info['bands'])) {

					$element_title .= '<div class="h2">';
					$element_title .= DatatypeHelper::arrayToString($info['bands'], ', ', '/bands/');
					$element_title .= '</div>';

				}

				$element_title .= '<h1 itemprop="name">'.$element->name.'</h1>';

				if(!empty($element->alt_name)) {

					$element_title .= '<div class="h3" itemprop="alternativeHeadline">'.$element->alt_name.'</div>';

				}

			$element_title .= '</div>';
		$element_title .= '</div>';

		$element_title .= '<div class="row mt-3">';
			$element_title .= '<div class="col-md-12">';

			if(isset($info['rate'])) {

				if (Auth::check()) {

					$element_title .= '<div><input class="main_rating" name="val" value="' . $info['rate'] . '" type="text"></div>';

				} else {

					$element_title .= DummyHelper::regToRate();

				}

			} else {

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

				$element_title .= '<p>';

				if(isset($info['publishers']) && count($info['publishers'])) {

					$element_title .= DatatypeHelper::arrayToString($info['publishers'], ', ', '/companies/', false, 'publisher');
					$element_title .= ', ';

				}

				if(!empty($element->year)) {

					$element_title .= '<a itemprop="datePublished" href="/years/'.$section.'/'.$element->year.'">'.$element->year.'</a>';
					$element_title .= ' г. ';

				}

				if(isset($info['countries'])) {

					$element_title .= DatatypeHelper::arrayToString(
						$info['countries'],
						', ',
						'/countries/'.$section.'/'
					);
					$element_title .= '. ';

				}

				if(isset($info['genres']) && count($info['genres'])) {

					$element_title .= DatatypeHelper::collectionToString(
						$info['genres'],
						'genre',
						', ',
						'/genres/'.$section.'/',
						false,
						'genre'
					);

				}

				if(!empty($element->length)) {

					$element_title .= '. ';
					$element_title .= '<meta itemprop="duration" content="T'.$element->length.'M" />'.$element->length.' мин. ';

				}

				$element_title .= '</p>';

				if(isset($info['directors']) && count($info['directors'])) {

					$element_title .= '<p>';
					$element_title .= 'Режиссер: '.DatatypeHelper::arrayToString(
						$info['directors'],
						', ',
						'/persons/',
						false,
						'director'
					);
					$element_title .= '</p>';

				}

				if(isset($info['screenwriters']) && count($info['screenwriters'])) {

					$element_title .= '<p>';
					$element_title .= 'Сценарий: '.DatatypeHelper::arrayToString(
						$info['screenwriters'],
						', ',
						'/persons/',
						false,
						'creator'
					);
					$element_title .= '</p>';

				}

				if(isset($info['producers']) && count($info['producers'])) {

					$element_title .= '<p>';
					$element_title .= 'Продюсер: '.DatatypeHelper::arrayToString(
						$info['producers'],
						', ',
						'/persons/',
						false,
						'producer'
					);
					$element_title .= '</p>';

				}

				if(isset($info['game_developers']) && count($info['game_developers'])) {

					$element_title .= '<p>';
					$element_title .= 'Разработчик: '.DatatypeHelper::arrayToString(
						$info['game_developers'],
						', ',
						'/companies/',
						false,
						'creator'
					);
					$element_title .= '</p>';

				}

				if(isset($info['game_publishers']) && count($info['game_publishers'])) {

					$element_title .= '<p>';
					$element_title .= 'Издатель: '.DatatypeHelper::arrayToString(
						$info['game_publishers'],
						', ',
						'/companies/',
						false,
						'publisher'
					);
					$element_title .= '</p>';

				}

				if(isset($info['game_platforms']) && count($info['game_platforms'])) {

					$element_title .= '<p>';
					$element_title .= 'Платформы: '.DatatypeHelper::arrayToString(
						$info['game_platforms'],
						', ',
						'/platforms/'.$section.'/'
					);
					$element_title .= '</p>';

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

				$element_body .= '<div class="card">';

					$element_body .= '<img itemprop="image" src="/data/img/covers/'.$section.'/'.$info['cover'].'.jpg" alt="'.$element->name.'" class="card-img-top" />';

					if(Auth::check()) {

						$element_body .= '<div class="card-body text-center">';

						$element_body .= '<div class="btn-group">';

						if(RolesHelper::isAdmin($request)) {

							$class = 'btn btn-sm btn-outline-success';
							$href = '/admin/edit/' . $section . '/' . $element->id;
							$element_body .= '<a role="button" class="' . $class . '" href="' . $href . '" title="Редактировать">';
							$element_body .= '&#9998;';
							$element_body .= '</a>';

						}

						if((isset($info['wanted'])) && (1 === $info['wanted'])) {
							$class = 'btn btn-sm btn-success';
							$handler = 'onclick="unlike(\'' . $section . '\', \'' . $element->id . '\')"';
						} else {
							$class = 'btn btn-sm btn-outline-success';
							$handler = 'onclick="like(\'' . $section . '\', \'' . $element->id . '\')"';
						}
						$element_body .= '<button type="button" class="' . $class . '" ' . $handler . ' id="want_' . $element->id . '" title="Хочу">';
						$element_body .= '&#10084;';
						$element_body .= '</button>';

						if((isset($info['not_wanted'])) && (1 === $info['not_wanted'])) {
							$class = 'btn btn-sm btn-danger';
							$handler = 'onclick="undislike(\'' . $section . '\', \'' . $element->id . '\')"';
						} else {
							$class = 'btn btn-sm btn-outline-danger';
							$handler = 'onclick="dislike(\'' . $section . '\', \'' . $element->id . '\')"';
						}
						$element_body .= '<button type="button" class="' . $class . '" ' . $handler . ' id="not_want_' . $element->id . '" title="Не хочу">';
						$element_body .= '&#9785;';
						$element_body .= '</button>';

						if (RolesHelper::isAdmin($request)) {

							$class = 'btn btn-sm btn-outline-danger';
							$href = '/admin/delete/' . $section . '/' . $element->id;
							$handler = 'onclick="return window.confirm(\'Удалить?\');"';
							$element_body .= '<a role="button" class="' . $class . '" href="' . $href . '" ' . $handler . ' title="Удалить">';
							$element_body .= '&#10006;';
							$element_body .= '</a>';

						}

						$element_body .= '</div>';

						$element_body .= '</div>';

					}

				$element_body .= '</div>';

			$element_body .= '</div>';

			$element_body .= '<div itemprop="description" class="col-md-9">';

				$element_body .= '<p>'.nl2br($element->description).'</p>';

				if(isset($info['actors']) && count($info['actors'])) {

					$element_body .= '<p>';
					$element_body .= 'В ролях: '.DatatypeHelper::arrayToString(
						$info['actors'],
						', ',
						'/persons/',
						false,
						'actor'
					);
					$element_body .= '</p>';

				}

				if(isset($info['top_genres']) && count($info['top_genres'])) {
					$element_body .= '<p>Жанры: ';
					$element_body .= DatatypeHelper::arrayToString($info['top_genres'], ', ', '/genres/books/');
					$element_body .= '</p>';
				}

				if(isset($info['tracks']) && count($info['tracks'])) {

					$element_body .= '<ol>';
					$element_body .= '<li>'.DatatypeHelper::objectToJsArray($info['tracks'], '</li><li>', true).'</li>';
					$element_body .= '</ol>';

					$element_body .= '<div class="btn-group">';
					$element_body .= DummyHelper::getExtLink('yandex_music', $element->name);
					$element_body .= DummyHelper::getExtLink('google_play', $element->name);
					//$element_body .= DummyHelper::getExtLink('discogs', $element->name);
					$element_body .= '</div>';

				}

				if(('films' == $section) && RolesHelper::isAdmin($request)) {

					$element_body .= '<p>';
					$element_body .= DummyHelper::getExtLink('rutracker', $element->name);
					$element_body .= '</p>';

				}

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

			if(isset($info['collections']) && count($info['collections'])) {
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

			if((isset($info['relations'])) && (0 < $info['relations'])) {
				$element_footer .= '<p>';
					$element_footer .= '<a href="/'.$section.'/'.$element->id.'/relations/">';
						$element_footer .= 'Связи ';
						$element_footer .= '('.$info['relations'].')';
					$element_footer .= '</a>';
				$element_footer .= '</p>';
			}

			$element_footer .= '</div>';
		$element_footer .= '</div>';

		if(RolesHelper::isAdmin($request)) {

			$element_footer .= '<p>';
				$element_footer .= '<a href="/'.$section.'/'.$element->id.'/relations/">Установить связи</a>';
			$element_footer .= '</p>';

		}

        if(isset($info['similar']) && count($info['similar'])) {

			$element_footer .= '<h3>Похожие</h3>';
			$element_footer .= ElementsHelper::getElements($request, $info['similar'], $section, $options);

        }

		return $element_footer;

	}

	/**
	 * @param $comments
	 * @param string $section
	 * @param int $element_id
	 * @return string
	 */
	public static function getCardComments($comments, string $section = '', int $element_id = 0) {

		$element_comments = '';

		$element_comments .= '<h3>Комментарии</h3>';

		$element_comments .= '<div class="row mt-3">';

			$element_comments .= '<div class="col-md-12">';

				$element_comments .= CommentsHelper::showCommentForm($section, $element_id);

				$element_comments .= '<div itemscope itemtype="http://schema.org/UserComments" class="comments">';

				$element_comments .= CommentsHelper::showComments($comments);

				$element_comments .= '</div>';

			$element_comments .= '</div>';

		$element_comments .= '</div>';

		return $element_comments;

	}

	/**
	 * @param string $section
	 * @param int $element_id
	 * @return string
	 */
	public static function getCardScripts(string $section = '', int $element_id = 0) {

		$element_scripts = '';

		$element_scripts .= '<form method="POST">';
		$element_scripts .= '<input type="hidden" name="element_section" id="element_section" value="'.$section.'" autocomplete="off">';
		$element_scripts .= '<input type="hidden" name="element_id" id="element_id" value="'.$element_id.'" autocomplete="off">';
		$element_scripts .= '</form>';

		if(Auth::check()) {

			$element_scripts .= '<script type="text/javascript" src="/data/js/card.js"></script>';

		}

		return $element_scripts;

	}

}