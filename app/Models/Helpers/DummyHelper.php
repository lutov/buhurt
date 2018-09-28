<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use DB;
use Cache;
use Illuminate\Http\Request;

class DummyHelper {

	/**
	 * @return string
	 */
	public static function regToRate() {

		return '<small class="text-muted">
			<a href="/user/register">Зарегистрируйтесь</a>
			или
			<a href="/user/login">войдите</a>,
			чтобы поставить оценку
		</small>';

	}


	/**
	 * @return string
	 */
	public static function regToComment() {

		return '<small class="text-muted">
			<a href="/user/register">Зарегистрируйтесь</a>
			или
			<a href="/user/login">войдите</a>,
			чтобы добавить комментарий
		</small>';

	}

	/**
	 * @return string
	 */
	public static function regToAdd() {

		return '<small class="text-muted">
			<a href="/user/register">Зарегистрируйтесь</a>
			или
			<a href="/user/login">войдите</a>,
			чтобы добавить элемент
		</small>';

	}

	/**
	 * @return string
	 */
	public static function regToRecommend() {

		return '<small class="text-muted">
			<a href="/user/register">Зарегистрируйтесь</a>
			или
			<a href="/user/login">войдите</a>,
			чтобы получать персональные рекомендации
		</small>';

	}

	/**
	 * @param $site
	 * @param $name
	 * @return string
	 */
	public static function getExtLink($site, $name) {

		$link = '';

		switch($site) {

			case 'kinopoisk':
				$protocol = 'http';
				$url = 'www.kinopoisk.ru/index.php';
				$query = 'first=yes&kp_query';
				$target = '_blank';
				$site_name = 'Кинопоиск';
				break;

			case 'worldart': // http://www.world-art.ru/search.php?public_search=Denpa&global_sector=all
				$protocol = 'http';
				$url = 'www.world-art.ru/search.php';
				$query = 'global_sector=all&public_search';
				$target = '_blank';
				$site_name = 'World Art';
				break;

			case 'wiki': // https://ru.wikipedia.org/w/index.php?search=евангелинон
				$protocol = 'https';
				$url = 'ru.wikipedia.org/w/index.php';
				$query = 'search';
				$target = '_blank';
				$site_name = 'Вики';
				break;

			case 'wiki_en': // https://en.wikipedia.org/w/index.php?search=евангелинон
				$protocol = 'https';
				$url = 'en.wikipedia.org/w/index.php';
				$query = 'search';
				$target = '_blank';
				$site_name = 'Wikipedia';
				break;

			case 'yandex': // https://yandex.ru/yandsearch?&text=евангелион
				$protocol = 'https';
				$url = 'yandex.ru/yandsearch';
				$query = 'text';
				$target = '_blank';
				$site_name = 'Яндекс';
				break;

			case 'yandex_images': // https://yandex.ru/images/search?text=евангелион
				$protocol = 'https';
				$url = 'yandex.ru/images/search';
				$query = 'iorient=vertical&text';
				$target = '_blank';
				$site_name = 'Яндекс.Картинки';
				break;

			case 'yandex_music': // https://music.yandex.ru/search?&text=евангелион
				$protocol = 'https';
				$url = 'music.yandex.ru/search';
				$query = 'text';
				$target = '_blank';
				$site_name = 'Яндекс.Музыка';
				break;

			case 'discogs': // https://www.discogs.com/search/?q=evangelion&type=release
				$protocol = 'https';
				$url = 'www.discogs.com/search/';
				$query = 'type=release&q';
				$target = '_blank';
				$site_name = 'Discogs';
				break;

			case 'rutracker': // http://rutracker.org/forum/tracker.php?nm=евангелион
				$protocol = 'http';
				$url = 'rutracker.org/forum/tracker.php';
				$query = 'nm';
				$target = '_blank';
				$site_name = 'Рутрекер';
				break;

			case 'fantlab': // https://fantlab.ru/searchmain?searchstr=евангелион
				$protocol = 'https';
				$url = 'fantlab.ru/searchmain';
				$query = 'searchstr';
				$target = '_blank';
				$site_name = 'Фантлаб';
				break;

			case 'google_play': // https://play.google.com/store/search?q=евангелион&c=music
				$protocol = 'https';
				$url = 'play.google.com/store/search';
				$query = 'c=music&q';
				$target = '_blank';
				$site_name = 'Google Play';
				break;

			default:
				$protocol = 'http';
				$url = $site;
				$query = '';
				$target = '_blank';
				$site_name = 'Ссылка';
				break;

		}

		$link .= '<a href="'.$protocol.'://'.$url.'?'.$query.'='.urlencode($name).'" target="'.$target.'" role="button" class="btn btn-sm btn-outline-primary">'.$site_name.'</a>';

		return $link;

	}

	/**
	 * @param Request $request
	 * @return string
	 */
	public static function getStats(Request $request) {

		$stats = '';

		$minutes = 60*24;
		$var_name = date('Ymd').'_stats';

		//Cache::forget($var_name);
		$stats_array = Cache::remember($var_name, $minutes, function() {
			//return DB::table('users')->get();

			$stats_array = array(
				'users' => 0,
				'rates' => 0,
				'comments' => 0,
				'books' => 0,
				'films' => 0,
				'games' => 0,
				'albums' => 0,
			);

			$stats_array['users'] 		= DB::table('users')->count();
			$stats_array['rates'] 		= DB::table('rates')->count();
			$stats_array['comments'] 	= DB::table('comments')->count();
			$stats_array['books'] 		= DB::table('books')->count();
			$stats_array['films'] 		= DB::table('films')->count();
			$stats_array['games'] 		= DB::table('games')->count();
			$stats_array['albums'] 		= DB::table('albums')->count();

			$stats_array['created_at'] 	= date('Y-m-d H:i:s');
			DB::table('stats')->insert($stats_array);

			return $stats_array;
		});

		$stats .= '<li class="nav-item"><a class="nav-link disabled" href="#">';
		$stats .= TextHelper::number($stats_array['books'], array('книга', 'книги', 'книг')).', ';
		$stats .= TextHelper::number($stats_array['films'], array('фильм', 'фильма', 'фильмов')).', ';
		$stats .= TextHelper::number($stats_array['games'], array('игра', 'игры', 'игр')).', ';
		$stats .= TextHelper::number($stats_array['albums'], array('альбом', 'альбома', 'альбомов'));
		$stats .= '</a></li>';

		if(RolesHelper::isAdmin($request)) {

			$stats .= '<li class="nav-item"><a class="nav-link" href="/users">';
			$stats .= TextHelper::number($stats_array['users'], array('пользователь', 'пользователя', 'пользователей'));
			$stats .= '</a></li>';

			$stats .= '<li class="nav-item"><a class="nav-link disabled" href="#">';
			$stats .= TextHelper::number($stats_array['rates'], array('оценка', 'оценки', 'оценок'));
			$stats .= '</a></li>';

			$stats .= '<li class="nav-item"><a class="nav-link disabled" href="#">';
			$stats .= TextHelper::number($stats_array['comments'], array('комментарий', 'комментария', 'комментариев'));
			$stats .= '</a></li>';

		}

		return $stats;

	}

	/**
	 * @param Request $request
	 * @param string $input_var
	 * @param string $session_var
	 * @param string $default_val
	 * @return array|string
	 */
	public static function inputOrSession(Request $request, string $input_var = '', string $session_var = '', string $default_val = '') {

		return $request->input($input_var, function() use ($request, $session_var, $default_val) {
			$tmp_var = $request->session()->get($session_var, function() use ($request, $session_var, $default_val) {
				$tmp_var = $default_val;
				$request->session()->put($session_var, $tmp_var);
				return $tmp_var;
			});
			return $tmp_var;
		});

	}

	/**
	 * @param string $search
	 * @return string
	 */
	public static function getQuickAddLinks(string $search = '') {

		$output_string = '';

		$output_string .= '<h3>Добавить произведение</h3>';

		$output_string .= '<ol>';

			$output_string .= '<li>';

				$output_string .= '<a href="/q_add/books/?new_name='.urlencode($search).'">Книгу</a>';
				$output_string .= '<ul>';

					$output_string .= '<li>';
					$output_string .= '<a href="/q_add/books/?new_name='.urlencode($search).'&template=marvel_book">';
					$output_string .= 'Marvel Comics';
					$output_string .= '</a>';
					$output_string .= '</li>';

					$output_string .= '<li>';
					$output_string .= '<a href="/q_add/books/?new_name='.urlencode($search).'&template=dc_book">';
					$output_string .= 'DC Comics';
					$output_string .= '</a>';
					$output_string .= '</li>';

					$output_string .= '<li>';
					$output_string .= '<a href="/q_add/books/?new_name='.urlencode($search).'&template=image_book">';
					$output_string .= 'Image Comics';
					$output_string .= '</a>';
					$output_string .= '</li>';

					$output_string .= '<li>';
					$output_string .= '<a href="/q_add/books/?new_name='.urlencode($search).'&template=valiant_book">';
					$output_string .= 'Valiant Comics';
					$output_string .= '</a>';
					$output_string .= '</li>';

				$output_string .= '</ul>';

			$output_string .= '</li>';

			$output_string .= '<li>';

				$output_string .= '<a href="/q_add/films/?new_name='.urlencode($search).'">Фильм</a>';
				$output_string .= '<ul>';

					$output_string .= '<li>';
					$output_string .= '<a href="/q_add/films/?new_name='.urlencode($search).'&template=anime">';
					$output_string .= 'Аниме';
					$output_string .= '</a>';
					$output_string .= '</li>';

					$output_string .= '<li>';
					$output_string .= '<a href="/q_add/films/?new_name='.urlencode($search).'&template=marvel_film">';
					$output_string .= 'Marvel Comics';
					$output_string .= '</a>';
					$output_string .= '</li>';

					$output_string .= '<li>';
					$output_string .= '<a href="/q_add/films/?new_name='.urlencode($search).'&template=dc_film">';
					$output_string .= 'DC Comics';
					$output_string .= '</a>';
					$output_string .= '</li>';

				$output_string .= '</ul>';

			$output_string .= '</li>';

			$output_string .= '<li>';
			$output_string .= '<a href="/q_add/games/?new_name='.urlencode($search).'">Игру</a>';
			$output_string .= '</li>';

			$output_string .= '<li>';
			$output_string .= '<a href="/q_add/albums/?new_name='.urlencode($search).'">Альбом</a>';
			$output_string .= '</li>';

		$output_string .= '</ol>';

		return $output_string;

	}

}