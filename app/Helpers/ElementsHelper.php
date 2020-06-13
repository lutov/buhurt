<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Helpers;

use App\Models\Data\Collection;
use App\Models\Data\Country;
use App\Models\Data\Genre;
use App\Models\Data\Platform;
use App\Models\Data\Section;
use App\Models\User\Unwanted;
use App\Models\User\Wanted;
use Illuminate\Http\Request;
use App\Models\Search\ElementGenre;
use App\Models\User\Rate;
use Illuminate\Support\Facades\Cache;
use Laravelrus\LocalizedCarbon\LocalizedCarbon;
use App\Models\User\Event;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Collective\Html\FormFacade as Form;
use ResizeCrop;

class ElementsHelper {

	/**
	 * @return array
	 */
	public static function getSortDirection() {
		return array(
			'asc' => 'А→Я',
			'desc' => 'Я→А'
		);
	}

	/**
	 * @param array $sort_options
	 * @param string $sort
	 * @param string $order
	 * @param int $page
	 * @return string
	 */
	public static function getSort(array $sort_options, string $sort, string $order, int $page = 1) {

		$elements_list = '';

		if(!empty($sort_options)) {

			$sort_direction = self::getSortDirection();

			$elements_list .= '<noindex><!--noindex-->';

			$elements_list .= Form::open(array('class' => 'sort', 'method' => 'GET'));

			$elements_list .= '<div class="input-group input-group-sm mb-3">';

			//$elements_list .= Form::hidden('view', $request->get('view', 'plates'));
			$elements_list .= Form::select('sort', $sort_options, $sort, array('class' => 'custom-select', 'autocomplete' => 'off'));
			$elements_list .= Form::select('order', $sort_direction, $order, array('class' => 'custom-select', 'autocomplete' => 'off'));

			$elements_list .= Form::hidden('page', $page);

			$elements_list .= '<div class="input-group-append">';
			$elements_list .= Form::submit('Сортировать', array('class' => 'btn btn-outline-secondary'));
			$elements_list .= '</div>';

			$elements_list .= Form::close();

			$elements_list .= '</div>';

			$elements_list .= '<!--/noindex--></noindex>';

		}

		return $elements_list;

	}

	/**
	 * @param Request $request
	 * @param array $options
	 * @return string
	 */
	public static function getHeader(Request $request, array $options) {

		$page = $request->get('page', 1);

		$elements_list = '';

		$elements_list .= self::getSort($options['sort_options'], $options['sort'], $options['order'], $page);

		$elements_list .= '<div class="album">';
		$elements_list .= '<div class="row">';

		return $elements_list;

	}

	/**
	 * @param string $section
	 * @param int $id
	 * @return string
	 */
	private static function bindFastRating(string $section, int $id) {
		// TODO попробовать назначать события как-то более аккуратно
		$elements_list = '';
		$elements_list .= '<script>';
		$elements_list .= '$(\'#rating_' . $section . '_' . $id . '\').on(\'rating:change\', function(event, value, caption) {';
		$elements_list .= 'var path = \'/rates/rate/' . $section . '/' . $id . '\';';
		$elements_list .= 'var params = {rate_val: value};';
		//$elements_list .= 'console.log(params);';
		$elements_list .= '$.post(path, params, function(data) {show_popup(data);});';
		$elements_list .= '$.post(\'/achievements\', {}, function(data) {show_popup(data);});';
		$elements_list .= '});';
		$elements_list .= '</script>';
		return $elements_list;
	}

	/**
	 * @param string $section
	 * @param $element
	 * @param $user
	 * @return string
	 */
	private static function getFastRating(string $section, $element, $user) {
		if(!method_exists($element, 'rates')) {return '';}
		$rate = self::getRate($element, $user);
		$elements_list = '';
		$elements_list .= '<div class="fast_rating_block">';
		$elements_list .= '<input name="val" value="'.$rate.'"';
		$elements_list .= ' class="fast_rating" id="rating_'.$section.'_'.$element->id.'"';
		$elements_list .= ' type="text" autocomplete="off">';
		$elements_list .= '</div>';
		$elements_list .= self::bindFastRating($section, $element->id);
		return $elements_list;
	}

	/**
	 * @param string $section
	 * @param $element
	 * @param $user
	 * @param bool $isAdmin
	 * @return string
	 */
	public static function getControls(string $section, $element, $user, bool $isAdmin = false) {

		$elements_list = '';

		$elements_list .= '<div class="mt-3">';
		$elements_list .= '<div class="btn-group">';

		$link = $section.'/'.$element->id;
		$call = "'".$section."', '".$element->id."'";
		$b_class = 'btn btn-sm';

		if($isAdmin) {
			$elements_list .= '<a role="button" class="'.$b_class.' btn-outline-success" href="/admin/edit/'.$link.'" title="Редактировать">';
			$elements_list .= '&#9998;';
			$elements_list .= '</a>';
		}

		if(method_exists($element, 'wanted')) {
			if (self::isWanted($element, $user)) {
				$class = ' btn-success';
				$handler = 'unset_wanted('.$call.')';
			} else {
				$class = ' btn-outline-success';
				$handler = 'set_wanted('.$call.')';
			}
			$elements_list .= '<button type="button" class="'.$b_class.$class.'" onclick="'.$handler.'" id="want_'.$element->id.'" title="Хочу">';
			$elements_list .= '&#10084;';
			$elements_list .= '</button>';

			if (self::isUnwanted($element, $user)) {
				$class = ' btn-danger';
				$handler = 'unset_unwanted('.$call.')';
			} else {
				$class = ' btn-outline-danger';
				$handler = 'set_unwanted('.$call.')';
			}
			$elements_list .= '<button type="button" class="'.$b_class.$class.'" onclick="'.$handler.'" id="not_want_'.$element->id.'" title="Не хочу">';
			$elements_list .= '&#9785;';
			$elements_list .= '</button>';
		}

		if ($isAdmin) {
			$elements_list .= '<a role="button" class="'.$b_class.' btn-outline-danger" href="/admin/delete/'.$link.'"';
			$elements_list .= ' onclick="return window.confirm(\'Удалить?\');" title="Удалить">';
			$elements_list .= '&#10006;';
			$elements_list .= '</a>';
		}

		$elements_list .= '</div>';
		$elements_list .= '</div>';

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

		if(is_object($element)) {

			$link = '/' . $section . '/' . $element->id;
			$cover = self::getCover($section, $element->id);

			$elements_list .= '<div class="col-lg-3 col-md-4 col-sm-6 col-6">';
			$elements_list .= '<div class="card mb-4 box-shadow">';

			$is_square = false;
			if('albums' == $section) {$is_square = true;}

			if($is_square) {
				$elements_list .= '<div class="card-img-box-square">';
			} else {
				$elements_list .= '<div class="card-img-box">';
			}

			$elements_list .= '<a href="'.$link.'">';

			$elements_list .= '<img class="card-img-top" src="'.$cover.'" alt="'.$element->name.'" loading="lazy" />';

			$elements_list .= '</a>';

			$elements_list .= '</div>';

			$elements_list .= '<div class="card-header">';

			$elements_list .= '<a href="'.$link.'">';
			$elements_list .= $element->name;
			$elements_list .= '</a>';

  			$elements_list .= '</div>';

			if(Auth::check()) {
				$user = Auth::user();
				$isAdmin = RolesHelper::isAdmin($request);
				$elements_list .= '<div class="card-body text-center d-none d-xl-block">';
				$elements_list .= self::getFastRating($section, $element, $user);
				$elements_list .= self::getControls($section, $element, $user, $isAdmin);
				$elements_list .= '</div>';
			}

			if($element->caption) {
				$elements_list .= '<div class="card-footer text-muted">';
				$elements_list .= $element->caption;
				$elements_list .= '</div>';
			}

			$elements_list .= '</div>';

			$elements_list .= '</div>';

		}

		return $elements_list;

	}

	/**
	 * @return string
	 */
	public static function getFooter() {
		$elements_list = '';
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
				'sort_options' => array(),
				'sort' => 'name',
				'order' => 'asc'
			);
		}

		if($options['header']) {$elements_list .= self::getHeader($request, $options);}
		foreach ($elements as $element) {
			$elements_list .= self::getElement($request, $element, $section, $options);
		}
		if($options['footer']) {$elements_list .= self::getFooter();}

		if ($options['paginate']) {
			if(!empty($request->get('sort'))) {
				$elements_list .= '<noindex><!--noindex-->';
				$elements_list .= $elements->appends(
					array(
						//'view' => $request->get('view', 'plates'),
						'sort' => $options['sort'],
						'order' => $options['order'],
					)
				)->render();
				$elements_list .= '<!--/noindex--></noindex>';
			} else {
				$elements_list .= $elements->render();
			}
		}

		return $elements_list;

	}

	/**
	 * @param Request $request
	 * @param $elements
	 * @param string $section
	 * @param array $options
	 * @return string
	 */
	public static function getList(Request $request, $elements, string $section, array $options = array()) {

		$elements_list = '';

		if(!count($options)) {
			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'count' => true,
			);
		}

		if($options['header']) {
			$elements_list .= self::getSort($options['sort_options'], $options['sort'], $options['order']);
		}

		if(isset($options['columns'])) {
			$elements_list .= '<div style="';
			$elements_list .= 'column-count: '.$options['columns']['count'].';';
			$elements_list .= ' column-width: '.$options['columns']['width'].';';
			$elements_list .= '">';
		}
		$elements_list .= '<ul class="list-unstyled">';
		foreach ($elements as $element) {

			if('' != $element->name) {
				$elements_list .= '<li>';
				$elements_list .= '<a href="/';
				if (!empty($section)) {
					$elements_list .= $section . '/';
				}
				if (isset($options['subsection'])) {
					$elements_list .= $options['subsection'] . '/';
				}
				$elements_list .= $element->id;
				if (isset($options['anchor'])) {
					$elements_list .= '#'.$options['anchor'];
				}
				$elements_list .= '">';
				$elements_list .= $element->name;
				$elements_list .= '</a>';
				if($options['count']) {
					$elements_list .= ' <span class="small text-secondary">('.$element->count.')</span>';
				}
				$elements_list .= '</li>';
			}

		}
		$elements_list .= '</ul>';
		if(isset($options['columns'])) {$elements_list .= '</div>';}

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
		$minutes = 60;
		$var_name = 'collection_'.$section.'';
		$rows = Cache::remember($var_name, $minutes, function () use ($type) {
			return Rate::where('element_type', '=', $type)
				->where('user_id', '=', 1)
				->where('rate', '>', 6)
				->get()
			;
		});
		if(!$rows->count()) {return '';}
		$rand_row = rand(0, $rows->count());
		if(isset($rows[$rand_row]) && !empty($rows[$rand_row])) {
			$element_id = $rows[$rand_row]->element_id;
			$element = $type::find($element_id);
			if (!empty($element)) {
				$result = self::getElement($request, $element, $section);
			} else {
				$result = self::getRecommend($request, $section);
			}
		}
		return $result;
	}

	/**
	 * @param array $options
	 * @return array
	 */
	public static function getSimilar(array $options = array()) {
		$sim_elem = [];
		if(count($options)) {
			$rand_id = DB::table('elements_genres')
				->orderBy(DB::raw('RAND()'))
				->where('element_type', '=', $options['type'])
				->where(function ($query) use ($options) {
					foreach($options['genres'] as $key => $value) {
						$query->orWhere('genre_id', '=', $value->id);
					}
				})
				->value('element_id')
			;
			$sim_elem = $options['type']::find($rand_id);
		}
		if(empty($sim_elem)
			|| (0 == $sim_elem->verified)
			|| ($options['element_id'] == $sim_elem->id)
		) {$sim_elem = self::getSimilar($options);}
		return $sim_elem;
	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param $element
	 * @param array $info
	 * @return string
	 */
	public static function getCardHeader(Request $request, string $section, $element, array $info = array()) {

		$element_title = '';

		$element_title .= '<div class="row mt-5 text-center">';
			$element_title .= '<div class="col-md-12">';

				if($element->writers) {

					$writers = DatatypeHelper::arrayToString($element->writers, ', ', '/persons/', false, 'author');

					$many_authors = (2 < mb_substr_count($writers, ', ')) ? true : false;

					if($many_authors) {

						$authors = explode(', ', $writers);

						$element_title .= '<abbr title="Произведения разных авторов" class="h2" data-toggle="collapse" data-target="#collapseAuthors" aria-expanded="false" aria-controls="collapseAuthors">';
						$element_title .= 'Альманах';
						$element_title .= '</abbr>';

						$element_title .= '<div class="h4 mt-2 collapse" id="collapseAuthors">';
						$element_title .= '<ul class="list-unstyled">';
						$element_title .= '<li class="mt-2">';
						$element_title .= implode('</li><li class="mt-2">', $authors);
						$element_title .= '</li>';
						$element_title .= '</ul>';
						$element_title .= '</div>';

					} else {
						$element_title .= '<div class="h2">';
						$element_title .= $writers;
						$element_title .= '</div>';
					}

				}

				if($element->bands) {
					$element_title .= '<div class="h2">';
					$element_title .= DatatypeHelper::arrayToString($element->bands, ', ', '/bands/');
					$element_title .= '</div>';
				}

				$element_title .= '<h1 itemprop="name" id="buhurt_name">'.$element->name.'</h1>';

				if(!empty($element->alt_name)) {

					$alt_name = '';
					
					$long_name = (1 < count($element->alt_name)) ? true : false;

					if($long_name) {

						$names = $element->alt_name;

						$alt_name .= '<div class="h4 d-none d-md-block" id="buhurt_alt_name">';
						$alt_name .= '<ul class="list-unstyled">';
						$alt_name .= '<li>';
						$alt_name .= '<abbr title="Альтернативные названия" class="" data-toggle="collapse" data-target="#collapseAltName" aria-expanded="false" aria-controls="collapseAltName" itemprop="alternativeHeadline">';
						$alt_name .= array_shift($names);
						$alt_name .= '</abbr>';
						$alt_name .= '</li>';
						$alt_name .= '<div class="collapse" id="collapseAltName">';
						$alt_name .= '<li itemprop="alternativeHeadline">';
						$alt_name .= implode('</li><li itemprop="alternativeHeadline">', $names);
						$alt_name .= '</li>';
						$alt_name .= '</div>';
						$alt_name .= '</ul>';
						$alt_name .= '</div>';
					} else {
						$alt_name .= '<div class="h4 d-none d-md-block" itemprop="alternativeHeadline" id="buhurt_alt_name">'.$element->alt_name[0].'</div>';
					}

					$element_title .= $alt_name;

				}

			$element_title .= '</div>';
		$element_title .= '</div>';

		$element_title .= '<div class="row d-md-block text-center">';

			$element_title .= '<div class="col-md-12">';

			$element_title .= '<div class="mb-2">';
			if (Auth::check()) {
				$user = Auth::user();
				$rate = self::getRate($element, $user);
				$element_title .= '<input class="main_rating" name="val" value="'.$rate.'" type="text">';
			} else {
				$element_title .= DummyHelper::regToRate();
			}
			$element_title .= '</div>';

			$rating = self::countRating($element);

			if($rating['count']) {

				$element_title .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="small text-secondary">';

				$element_title .= '<meta itemprop="worstRating" content = "1">';
				$element_title .= 'Средняя оценка: <b itemprop="ratingValue">'.$rating['average'].'</b>';
				$element_title .= '<meta itemprop="bestRating" content = "10">';

				$element_title .= ', ';
				$element_title .= TextHelper::ratingCount($rating['count'], array('голос', 'голоса', 'голосов'));

				if(0 != $element->comments->count()) {
					$element_title .= ', ';
					$element_title .= TextHelper::reviewCount($element->comments->count(), array('комментарий', 'комментария', 'комментариев'));
				}

				$element_title .= '</div>';

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
	public static function getCardBody(Request $request, string $section, $element, array $info = array()) {

		$isAdmin = RolesHelper::isAdmin($request);

		$element_body = '';

		$element_body .= '<div class="row mt-3">';

			$element_body .= '<div class="col-md-3 col-6 mb-3">';

				$element_body .= '<div class="card">';

					$cover = self::getCover($section, $element->id);

					$element_body .= '<img itemprop="image" src="'.$cover.'" alt="'.$element->name.'" class="card-img-top buhurt-cover" />';

					if(Auth::check()) {
						$user = Auth::user();
						$element_body .= '<div class="card-body text-center d-none d-xl-block">';
						$element_body .= self::getControls($section, $element, $user, $isAdmin);
						$element_body .= '</div>';
					}

				$element_body .= '</div>';

			$element_body .= '</div>';

			$element_body .= '<div class="col-md-9 col-12" id="elementDetails">';

				$card_details = self::getCardDetails($section, $element, $isAdmin);
				if(!empty($card_details)) {
					$element_body .= '<div class="border rounded p-3" id="cardDetails">';
					$element_body .= $card_details;
					$element_body .= '</div>';
				}

			$element_body .= '</div>';

		$element_body .= '</div>';

		return $element_body;

	}

	/**
	 * @param string $section
	 * @param $element
	 * @param bool $isAdmin
	 * @return string
	 */
	public static function getCardDetails(string $section, $element, bool $isAdmin = false) {

		$element_body = '';

		if(!empty(trim($element->description))) {

			$element_body .= '<div class="mt-0 mb-4" itemprop="description">';
			$element_body .= nl2br($element->description);
			$element_body .= '</div>';

		}

		if($element->tracks) {

			$element_body .= '<ol>';
			$element_body .= '<li>'.DatatypeHelper::objectToJsArray(
					$element->tracks,
					'</li><li>',
					true
				).'</li>';
			$element_body .= '</ol>';

			$element_body .= '<div class="btn-group">';
			$element_body .= DummyHelper::getExtLink('yandex_music', $element->name);
			$element_body .= DummyHelper::getExtLink('google_play', $element->name);
			//$element_body .= DummyHelper::getExtLink('discogs', $element->name);
			$element_body .= '</div>';

		}

		$main_info = '';

		if($element->books_publishers && $element->books_publishers->count()) {
			$main_info .= DatatypeHelper::arrayToString(
				$element->books_publishers,
				', ',
				'/companies/',
				false,
				'publisher'
			);
			$main_info .= ', ';
		}

		if($element->year) {
			$main_info .= '<a itemprop="datePublished" href="/years/'.$element->year.'">'.$element->year.'</a>';
			$main_info .= ' г. ';
		}

		if($element->countries) {
			$main_info .= DatatypeHelper::arrayToString(
				$element->countries,
				', ',
				'/countries/'
			);
			$main_info .= '. ';
		}

		if($element->genres) {
			$main_info .= DatatypeHelper::arrayToString(
				$element->genres,
				', ',
				'/genres/',
				false,
				'genre'
			);
		}

		if($element->length) {
			$main_info .= '. ';
			$main_info .= '<meta itemprop="duration" content="T'.$element->length.'M" />'.$element->length.' мин. ';
		}

		if(!empty($main_info)) {
			$element_body .= '<div class="mt-4 mb-4 small">';
			$element_body .= $main_info;
			$element_body .= '</div>';
		}

		if($element->directors && $element->directors->count()) {
			$element_body .= '<div class="mt-2 mb-2 small">';
			$element_body .= 'Режиссер: '.DatatypeHelper::arrayToString(
					$element->directors,
					', ',
					'/persons/',
					false,
					'director'
				);
			$element_body .= '</div>';
		}

		if($element->screenwriters && $element->screenwriters->count()) {
			$element_body .= '<div class="mt-2 small">';
			$element_body .= 'Сценарий: '.DatatypeHelper::arrayToString(
					$element->screenwriters,
					', ',
					'/persons/',
					false,
					'creator'
				);
			$element_body .= '</div>';
		}

		if($element->producers && $element->producers->count()) {
			$element_body .= '<div class="mt-2 small">';
			$element_body .= 'Продюсер: '.DatatypeHelper::arrayToString(
					$element->producers,
					', ',
					'/persons/',
					false,
					'producer'
				);
			$element_body .= '</div>';
		}

		if($element->actors && $element->actors->count()) {
			$element_body .= '<div class="mt-4 small">';
			$element_body .= 'В ролях: '.DatatypeHelper::arrayToString(
					$element->actors,
					', ',
					'/persons/',
					false,
					'actor'
				);
			$element_body .= '</div>';
		}

		if($element->developers && $element->developers->count()) {
			$element_body .= '<div class="mt-2 small">';
			$element_body .= 'Разработчик: '.DatatypeHelper::arrayToString(
					$element->developers,
					', ',
					'/companies/',
					false,
					'creator'
				);
			$element_body .= '</div>';
		}

		if($element->games_publishers && $element->games_publishers->count()) {
			$element_body .= '<div class="mt-2 small">';
			$element_body .= 'Издатель: '.DatatypeHelper::arrayToString(
					$element->games_publishers,
					', ',
					'/companies/',
					false,
					'publisher'
				);
			$element_body .= '</div>';
		}

		if($element->platforms && $element->platforms->count()) {
			$element_body .= '<div class="mt-2 small">';
			$element_body .= 'Платформы: '.DatatypeHelper::arrayToString(
					$element->platforms,
					', ',
					'/platforms/'
				);
			$element_body .= '</div>';
		}

		if($element->collections && $element->collections->count()) {
			$element_body .= '<div class="mt-4 small">';
			$element_body .= 'Коллекции: ';
			$element_body .= DatatypeHelper::arrayToString(
				$element->collections,
				', ',
				'/collections/',
				false,
				"isPartOf"
			);
			$element_body .= '</div>';
		}

		if(
			($element->relations && $element->relations->count())
			||
			($isAdmin && method_exists($element, 'relations'))
		) {
			$element_body .= '<div class="mt-4 small">';
			$element_body .= '<a href="/'.$section.'/'.$element->id.'/relations/">';
			$element_body .= 'Связанные произведения ';
			if($element->relations) {
				$element_body .= '(' . $element->relations->count() . ')';
			}
			$element_body .= '</a>';
			$element_body .= '</div>';
		}

		return $element_body;

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param $element
	 * @param array $info
	 * @return string
	 */
	public static function getCardFooter(Request $request, string $section, $element, array $info = array()) {
		$options = array(
			'header' => true,
			'paginate' => false,
			'footer' => true,
			'sort_options' => array(),
			'sort' => 'name',
			'order' => 'asc',
		);
		$element_footer = '';
        if(isset($info['similar']) && count($info['similar'])) {
			$element_footer .= '<h3 class="mt-5 mb-3">Похожие</h3>';
			$element_footer .= self::getElements($request, $info['similar'], $section, $options);
        }
		return $element_footer;
	}

	/**
	 * @param Request $request
	 * @param $comments
	 * @param string $section
	 * @param int $element_id
	 * @return string
	 */
	public static function getCardComments(Request $request, $comments, string $section = '', int $element_id = 0) {

		$element_comments = '';

		$element_comments .= '<h3 id="reviews">Комментарии</h3>';

		$element_comments .= '<div class="row mt-3">';

			$element_comments .= '<div class="col-md-12">';

				$element_comments .= CommentsHelper::showCommentForm($request, $section, $element_id);

				$element_comments .= '<div itemscope itemtype="http://schema.org/UserComments" class="comments">';

				$element_comments .= CommentsHelper::showComments($request, $comments);

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

	/**
	 * @param Event $element
	 * @param bool $no_br
	 * @return string
	 */
	public static function getEvent(Event $element, bool $no_br = false) {

		$type = $element->element_type;
		$section = SectionsHelper::getSectionBy($type);

		$user_id = $element->user_id;
		$user = User::find($user_id);

		$elements_text = '';

		$file_path = public_path() . '/data/img/avatars/' . $user_id . '.jpg';

		$elements_text .= '<div class="container-fluid border bg-light mt-3" id="element_' . $element->id . '">';

		$elements_text .= '<div class="row mt-3 mb-3">';

		$elements_text .= '<div class="col-md-6">';

		$elements_text .= '<a href="/user/' . $user_id . '/profile">' . $user->username . '</a>';
		$elements_text .= ', ';
		$elements_text .= LocalizedCarbon::instance($element->created_at)->diffForHumans();

		$elements_text .= '</div>';

		$elements_text .= '</div>';

		$elements_text .= '<div class="row mt-3 mb-3">';

		$elements_text .= '<div class="col-lg-1 d-none d-lg-block">';

		if (file_exists($file_path)) {

			$elements_text .= '<a href="/user/' . $user_id . '/profile"><img src="/data/img/avatars/' . $user_id . '.jpg" width="" alt="" class="img-fluid border" /></a>';
		}

		$elements_text .= '</div>';

		$elements_text .= '<div class="col-12 col-lg-11">';

		$elements_text .= '<div class="p-3 bg-white border" id="element_' . $element->id . '_text">';

		$elements_text .= '<p>';
		$elements_text .= '<a href="/'.$section.'/'.$element->element_id.'">';
		$elements_text .= $element->name;
		$elements_text .= '</a>';
		$elements_text .= '</p>';

		if(!empty($element->text)) {

			$elements_text .= '<p>';
			$elements_text .= nl2br($element->text);
			$elements_text .= '</p>';

		}

		$elements_text .= '</div>';

		$elements_text .= '</div>';

		$elements_text .= '</div>';

		$elements_text .= '</div>';

		if ($no_br) {
			$elements_text = str_replace("\n", ' ', $elements_text);
			$elements_text = addslashes($elements_text);
		}

		return $elements_text;

	}

	/**
	 * @param Request $request
	 * @param $elements
	 * @param string $section
	 * @param string $subsection
	 * @param array $options
	 * @return string
	 */
	public static function getEvents(Request $request, $elements, string $section = '', string $subsection = '', array $options = array()) {
		$elements_list = '';
		if(!count($options)) {
			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
			);
		}
		foreach ($elements as $element) {
			$elements_list .= self::getEvent($element);
		}
		if ($options['paginate']) {
			$elements_list .= '<div class="mt-5">';
			$elements_list .= $elements->render();
			$elements_list .= '</div>';
		}
		return $elements_list;
	}

	/**
	 * @param int $id
	 * @param string $section
	 * @param string $type
	 */
	public static function deleteElement(int $id = 0, string $section = '', string $type = '') {

		Rate::where('element_id', '=', $id)
			->where('element_type', '=', $type)
			->delete()
		;

		ElementGenre::where('element_id', '=', $id)
			->where('element_type', '=', $type)
			->delete()
		;

		self::deleteCover($section, $id);

		$type::find($id)->delete();

	}

	/**
	 * @param Section $section
	 * @param int $donor_id
	 * @param int $recipient_id
	 */
	public static function transfer(Section $section, int $donor_id, int $recipient_id) {

		$rates = Rate::where('element_type', '=', $section->type)
			->where('element_id', '=', $recipient_id)
			->pluck('id')
			->toArray()
		;

		Rate::where('element_type', '=', $section->type)
			->where('element_id', '=', $donor_id)
			->whereNotIn('id', $rates)
			->update(array('element_id' => $recipient_id))
		;

		self::deleteElement($donor_id, $section->alt_name, $section->type);

	}

	/**
	 * @param string $section
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getGenres(string $section, bool $cache = true) {
		$minutes = 60;
		$var_name = $section.'_genres';
		$type = SectionsHelper::getSectionType($section);
		if($cache) {
			$result = Cache::remember($var_name, $minutes, function () use ($type) {
				return Genre::where('element_type', $type)->orderBy('name')->get();
			});
		} else {
			$result = Genre::where('element_type', $type)->orderBy('name')->get();
		}
		return $result;
	}

	/**
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getCollections(bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = 'collections';
			$result = Cache::remember($var_name, $minutes, function () {
				return Collection::orderBy('name')->get();
			});
		} else {
			$result = Collection::orderBy('name')->get();
		}
		return $result;
	}

	/**
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getCountries(bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = 'countries';
			$result = Cache::remember($var_name, $minutes, function () {
				return Country::orderBy('name')->get();
			});
		} else {
			$result = Country::orderBy('name')->get();
		}
		return $result;
	}
	/**
	 * @param bool $cache
	 * @return mixed
	 */
	public static function getPlatforms(bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = 'platforms';
			$result = Cache::remember($var_name, $minutes, function () {
				return Platform::orderBy('name')->get();
			});
		} else {
			$result = Platform::orderBy('name')->get();
		}
		return $result;
	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param int $element_id
	 */
	public static function setCover(Request $request, string $section, int $element_id) {
		$covers_path = '/data/img/covers/';
		$extension = '.webp';
		$path = public_path().$covers_path.$section;
		$fileName = $element_id.$extension;
		$full_path = $path.'/'.$fileName;
		if ($request->hasFile('cover')) {
			if (file_exists($full_path)) {
				unlink($full_path);
			}
			self::resizeCrop($request->file('cover')->getRealPath(), $full_path);
		}
	}

	/**
	 * @param string $section
	 * @param int $id
	 * @return int
	 */
	public static function getCover(string $section, int $id) {
		$covers_path = '/data/img/covers/';
		$extension = '.webp';
		$rel_path = $covers_path.$section.'/'.$id.$extension;
		$file_path = public_path().$rel_path;
		if (file_exists($file_path)) {
			$hash = md5_file($file_path);
			$cover = $rel_path.'?hash='.$hash;
		} else {
			$rel_path = $covers_path.$section.'/0'.$extension;
			$file_path = public_path().$rel_path;
			$hash = md5_file($file_path);
			$cover = $rel_path.'?hash='.$hash;
		}
		return $cover;
	}

	/**
	 * @param string $section
	 * @param int $id
	 */
	public static function deleteCover(string $section, int $id) {
		$covers_path = '/data/img/covers/';
		$extension = '.webp';
		$rel_path = $covers_path.$section.'/'.$id.$extension;
		$file_path = public_path().$rel_path;
		if (file_exists($file_path)) {unlink($file_path);}
	}


	/**
	 * @param $real_path
	 * @param $full_path
	 */
	private static function resizeCrop($real_path, $full_path) {

		$width = 185 * 2;
		$height = 270 * 2;

		$resize = ResizeCrop::resize($real_path, $full_path, $width, 0);
		$size = getimagesize($full_path);

		/*
		if($height > $size[1]) {
			$diff = ($height - $size[1]) / 2;
			$crop = ResizeCrop::crop($full_path, $full_path, array(0, -$diff, $width, ($height - $diff)));
		}
		*/

	}

	/**
	 * @param $element
	 * @param $user
	 * @return int
	 */
	public static function getRate($element, $user) {
		$rate = 0;
		$user_rate = $element->rates
			->where('user_id', $user->id)
			->first()
		;
		if(isset($user_rate->rate)) {$rate = $user_rate->rate;}
		return $rate;
	}

	/**
	 * @param $element
	 * @param User $user
	 * @return mixed
	 */
	public static function isWanted($element, User $user) {
		return $element->wanted
			->where('user_id', $user->id)
			->first()
		;
	}

	/**
	 * @param $element
	 * @param User $user
	 * @return mixed
	 */
	public static function isUnwanted($element, User $user) {
		return $element->unwanted
			->where('user_id', $user->id)
			->first()
		;
	}

	/**
	 * @param Section $section
	 * @param int $user_id
	 * @param array $cache
	 * @return mixed
	 */
	public static function getWanted(Section $section, int $user_id, array $cache = array()) {
		if(count($cache)) {
			$minutes = $cache['minutes'];
			$var_name =  $cache['name'].'_wanted_'.$section->alt_name;
			$wanted = Cache::remember($var_name, $minutes, function () use ($section, $user_id) {
				return Wanted::select('element_id')
					->where('element_type', '=', $section->type)
					->where('user_id', '=', $user_id)
					->pluck('element_id')
					->toArray()
				;
			});
		} else {
			$wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('user_id', '=', $user_id)
				->pluck('element_id')
				->toArray()
			;
		}
		return $wanted;
	}

	/**
	 * @param Section $section
	 * @param int $user_id
	 * @param array $cache
	 * @return mixed
	 */
	public static function getUnwanted(Section $section, int $user_id, array $cache = array()) {
		if(count($cache)) {
			$minutes = $cache['minutes'];
			$var_name =  $cache['name'].'_unwanted_'.$section->alt_name;
			$unwanted = Cache::remember($var_name, $minutes, function () use ($section, $user_id) {
				return Unwanted::select('element_id')
					->where('element_type', '=', $section->type)
					->where('user_id', '=', $user_id)
					->pluck('element_id')
					->toArray()
				;
			});
		} else {
			$unwanted = Unwanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('user_id', '=', $user_id)
				->pluck('element_id')
				->toArray()
			;
		}
		return $unwanted;
	}

}