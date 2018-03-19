<?php namespace App\Models;

use DB;
use Auth;
use Form;
use Input;
use Cache;
use Config;
use Illuminate\Database\Eloquent\Model;
use Laravelrus\LocalizedCarbon\LocalizedCarbon;

class Helpers extends Model {

	/**
	 * @param $input_text - исходная строка
	 * @param int $limit - количество слов по умолчанию
	 * @param string $end_str - символ/строка завершения. Вставляется в конце обрезанной строки
	 * @return string
	 */
	public static function words_limit($input_text, $limit = 50, $end_str = '…') {
		$input_text = strip_tags($input_text);
		$words = explode(' ', $input_text); // создаём из строки массив слов
		if ($limit < 1 || sizeof($words) <= $limit) { // если лимит указан не верно или количество слов меньше лимита, то возвращаем исходную строку
			return $input_text;
		}
		$words = array_slice($words, 0, $limit); // укорачиваем массив до нужной длины
		$out = implode(' ', $words);
		return $out.$end_str; //возвращаем строку + символ/строка завершения
	}

	/**
	 * @param $object
	 * @param string $delimiter
	 * @param string $path
	 * @param bool $no_link
	 * @return string $string
	 */
	public static function array2string($object, $delimiter = ', ', $path = '/', $no_link = false, $itemprop = false) {
		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property)
		{
			if (!$no_link) {
				$string .= '<a href="'.$path.$property->id.'"';
				if($itemprop) {
					$string .= ' itemprop="'.$itemprop.'" ';
				}
				$string .= '>';
			}
				$string .= $property->name;
			if (!$no_link) {$string .= '</a>';}
			if ($i < $number)
			{
				$string .= $delimiter;
			}
			$i++;
		}
		return $string;
	}

	/**
	 * @param $object
	 * @param string $type
	 * @param string $delimiter
	 * @param string $path
	 * @param bool $no_link
	 * @return string $string
	 */
	public static function collection2string($object, $type = 'collection', $delimiter = ', ', $path = '/', $no_link = false, $itemprop = false) {
		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property)
		{
			if (!$no_link) {$string .= '<a href="'.$path.$property->$type->id.'"';
				if($itemprop) {
					$string .= ' itemprop="'.$itemprop.'" ';
				}
				$string .= '>';
			}
				$string .= $property->$type->name;
			if (!$no_link) {$string .= '</a>';}
			if ($i < $number)
			{
				$string .= $delimiter;
			}
			$i++;
		}
		return $string;
	}


	/**
	 * @param $object
	 * @param string $delimiter
	 * @param bool $no_quotes
	 * @return string
	 */
	public static function object2js_array($object, $delimiter = ', ', $no_quotes = false)
	{
		$string = '';
		$number = count($object);
		$i = 1;
		foreach ($object as $property)
		{
			if (!$no_quotes) {$string .= '"';}
				$string .= $property->name;
			if (!$no_quotes) {$string .= '"';}
			if ($i < $number)
			{
				$string .= $delimiter;
			}
			$i++;
		}
		return $string;
	}


	/**
	 * @param $object
	 * @param string $id
	 * @return string
	 */
	public static function object2list($object, $id='')
	{
		$string = '<ul id="'.$id.'">';

		foreach ($object as $property)
		{
			$string .= '<li>';
				$string .= $property->name;
			$string .= '</li>';

		}

		$string .= '</ul>';

		return $string;
	}


	/**
	 * @return string
	 */
	public static function reg2rate()
	{
		return '<p class="note">
			<span class="symlink" onclick="show_registration();">Зарегистрируйтесь</span>
			или
			<span class="symlink" onclick="show_entrance();">войдите</span>
			чтобы поставить оценку
		</p>';
	}


	/**
	 * @return string
	 */
	public static function reg2comment()
	{
		return '<p class="note">
			<span class="symlink" onclick="show_registration();">Зарегистрируйтесь</span>
			или
			<span class="symlink" onclick="show_entrance();">войдите</span>
			чтобы добавить комментарий
		</p>';
	}

	/**
	 * @return string
	 */
	public static function reg2add()
	{
		return '<p class="note">
			<span class="symlink" onclick="show_registration();">Зарегистрируйтесь</span>
			или
			<span class="symlink" onclick="show_entrance();">войдите</span>
			чтобы добавить элемент
		</p>';
	}


	/**
	 * @param object $elements
	 * @param string $section
	 * @param array $sort_options
	 * @param bool $paginate
	 * @param bool $switch2table
	 * @return string
	 */
	public static function get_elements($elements, $section = '', $sort_options = array(), $paginate = true, $switch2table = false) {

		$elements_list = '';
		$default_sort = $section.'.created_at';

		//var_dump($elements[0]->rates[0]->rate);

		if(!empty($sort_options)) {

			$sort_direction = Helpers::get_sort_direction();

			$elements_list .= Form::open(array('class' => 'sort', 'method' => 'GET'));
			$elements_list .= Form::hidden('view', Input::get('view', 'plates'));
			$elements_list .= Form::select('sort', $sort_options, Input::get('sort', $default_sort));
			$elements_list .= Form::select('sort_direction', $sort_direction, Input::get('sort_direction', 'desc'));
			$elements_list .= Form::hidden('page', Input::get('page', 1));
			$elements_list .= '&nbsp;';
			$elements_list .= Form::submit('Сортировать');
			$elements_list .= Form::close();

		}

		$view = Input::get('view', 'plates');
		if('table' == $view)
		{
			if($switch2table)
			{
				$elements_list .= Form::open(array('class' => 'switch2table', 'method' => 'GET')); // 'url' => Helpers::append_url_param('table_view', 'true'),
				$elements_list .= Form::hidden('view', 'plates');
				$elements_list .= Form::hidden('sort', Input::get('sort', $default_sort));
				$elements_list .= Form::hidden('sort_direction', Input::get('sort_direction', 'desc'));
				$elements_list .= Form::hidden('page', Input::get('page', 1));
				$elements_list .= Form::submit('Показать плитки');
				$elements_list .= Form::close();
			}

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
                            */
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
		}
		else
		{
			if($switch2table)
			{
				$elements_list .= Form::open(array('class' => 'switch2table', 'method' => 'GET')); // 'url' => Helpers::append_url_param('table_view', 'true'),
				$elements_list .= Form::hidden('view', 'table');
				$elements_list .= Form::hidden('sort', Input::get('sort', $default_sort));
				$elements_list .= Form::hidden('sort_direction', Input::get('sort_direction', 'desc'));
				$elements_list .= Form::hidden('page', Input::get('page', 1));
				$elements_list .= Form::submit('Показать таблицу');
				$elements_list .= Form::close();
			}

			$elements_list .= '<ul class="elements">';
			$default_cover = 0;

			foreach ($elements as $element) {

				if(is_object($element)) {
					$file_path = public_path() . '/data/img/covers/' . $section . '/' . $element->id . '.jpg';
					if (file_exists($file_path)) {
						$element_cover = $element->id;
					} else {
						$element_cover = $default_cover;
					}

					$elements_list .= '<li>';
					$elements_list .= '<a href="/' . $section . '/' . $element->id . '">';
					$elements_list .= '<p class="element_name">';
					//$elements_list .= '<strong>' . preg_replace('/ \(.+\)?/i', '', $element->name) . '</strong>';
					//$elements_list .= '<strong>'.$element->name.'</strong>';
					$elements_list .= $element->name;
					if (isset($element->rates) && 0 != count($element->rates) && Auth::check()) {
						$user_id = Auth::user()->id;
						$rate = $element
							->rates
							->where('user_id', $user_id)
							->toArray();
						if (0 != count($rate)) {
							$elements_list .= '&nbsp;—&nbsp;<strong>' . array_shift($rate)['rate'] . '</strong>';
						}
					}
					$elements_list .= '</p>';
					$elements_list .= '<img src="/data/img/covers/' . $section . '/' . $element_cover . '.jpg" alt="' . $element->name . ' (' . $element->year . ')" />';
					$elements_list .= '</a>';
					$elements_list .= '</li>';
				}
			}

			$elements_list .= '</ul>';
		}

		if ($paginate) {
			$elements_list .= $elements->appends(
				array(
					'view' => Input::get('view', 'plates'),
					'sort' => Input::get('sort', $default_sort),
					'sort_direction' => Input::get('sort_direction', 'desc')
				)
			)->render();
		}

		return $elements_list;
	}


	/**
	 * @param $element
	 * @param $section
	 * @return string
	 */
	public static function get_element($element, $section, $title = '', $no_rate = false) {

		$elements_list = '';
	
		$elements_list .= '<ul class="elements">';
		$default_cover = 0;

			$file_path = public_path() . '/data/img/covers/' . $section . '/' . $element->id . '.jpg';
			if (file_exists($file_path)) {
				$element_cover = $element->id;
			} else {
				$element_cover = $default_cover;
			}
			
			$elements_list .= '<li>';
			
			$elements_list .= '<a href="/' . $section . '/' . $element->id . '">';
			$elements_list .= '<p class="element_name">';			
			if(!empty($title)) {$elements_list .= $title;}			
			$elements_list .= $element->name;
			if (!$no_rate && (isset($element->rates) && 0 != count($element->rates))) {
				$rate = $element->rates->toArray();
				$elements_list .= '&nbsp;—&nbsp;<strong>' . array_shift($rate)['rate'] . '</strong>';
			}
			$elements_list .= '</p>';
			$elements_list .= '<img src="/data/img/covers/' . $section . '/' . $element_cover . '.jpg" alt="' . $element->name . ' (' . $element->year . ')" />';
			$elements_list .= '</a>';
			$elements_list .= '</li>';

		$elements_list .= '</ul>';

		return $elements_list;

	}


	public static function get_list($elements, $section, $subsection, $sort_options = array(), $paginate = true)
	{

		$elements_list = '';
		$default_sort = 'name';

		//var_dump($elements[0]->rates[0]->rate);

		if(!empty($sort_options)) {

			$sort_direction = Helpers::get_sort_direction();

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

	public static function get_sort_direction()
	{
		$sort_direction = array(
			'asc' => 'А→Я',
			'desc' => 'Я→А'
		);

		return $sort_direction;
	}


	/**
	 * @param $param
	 * @param $value
	 * @return string
	 */
	public static function append_url_param($param, $value)
	{
		$params = $_SERVER['QUERY_STRING'];

		if(!empty($params)) {$params .= '&';}
		$params .= $param.'='.$value;

		return '?'.$params;
	}


	/**
	 * @param $news
	 * @return string
	 */
	public static function read_news($news)
	{
		$result = '<div class="news">';

		foreach ($news as $value) {
			$result .= '<p>';
				$result .= '<b>'.$value->name.'</b></br>'.$value->text;
			$result .= '</p>';
		}

		$result .= '</div>';

		return $result;
	}


	/**
	 * @param $n
	 * @param $titles
	 * @return mixed
	 */
	public static function number($n, $titles) {
		$cases = array(2, 0, 1, 1, 1, 2);
		return $n.' '.$titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
	}


	/**
	 * @param $achievements
	 * @param $user_achievements
	 * @return string
	 */
	public static function render_achievements($achievements, $user_achievements)
	{
		$in_line = 7;
		$i = 1;

		$result = '<table class="achievements">';

		foreach($achievements as $value)
		{
			if($i == 1) {$result .= '<tr>';}

				$is_achieved = array_search($value->id, $user_achievements);

				$result .= '<td';
				if(false !== $is_achieved) {
					$path = '/data/img/achievements/'.$value->id.'.png';
					$result .=' class="achieved"';
					$content = '<img src="'.$path.'" alt="'.$value->name.'" />';
				}
				else
				{
					$content = '?';
				}
				//$result .= ' id="achievement'.$value->id.'"';
				$result .= ' title="'.$value->description.'"';
				$result .='>';
					$result .= $content;
				$result .= '</td>';

			if($i == $in_line) {$result .= '</tr>'; $i = 0;}
			$i++;
		}

		$result .= '</table>';

		return $result;
	}


	/**
	 * @param $comments
	 * @return string
	 */
	public static function show_comments($comments)
	{
		//echo '<pre>'.print_r($comments, true).'</pre>';

		$comments_list = '';
		
		foreach($comments as $key => $comment)
		{
			$comments_list .= Helpers::render_comment($comment);
		}
		
		return $comments_list;
	}


	/**
	 * @return string
	 */
	public static function show_comment_form (){

		if(Auth::check()) {

			$form = '
				<div class="comment_add">
					<span class="symlink" onclick="show_comment_form();">Написать комментарий</span>
					<div id="comment_form">
						'.Form::open(array('action' => 'CommentController@add', 'class' => 'comment_form', 'method' => 'POST')).'
							'.Form::textarea('comment', $value = null, $attributes = array('placeholder' => 'Комментарий', 'class' => 'half', 'id' => 'comment')).'
							'.Form::hidden('comment_id', $value = null, $attributes = array('id' => 'comment_id', 'autocomplete' => 'off')).'
							<br/>
							'.Form::button('Сохранить', $attributes = array('id' => 'comment_save')).'
						'.Form::close().'
					</div>
				</div>';

			return $form;

		} else {

			return Helpers::reg2comment();

		}

	}


	/**
	 * @param $comment
	 * @param bool $no_br
	 * @return string
	 */
	public static function render_comment($comment, $no_br = false) {

		$user_id = $comment->user_id;
		$user = User::find($user_id);

		$user_options = $user
			->options()
			->where('enabled', '=', 1)
			->pluck('option_id')
			->toArray();

		$is_my_private = in_array(1, $user_options);

		$comments_text = '';

		if(!$is_my_private || (Auth::check() && $user_id == Auth::user()->id)) {

			$file_path = public_path() . '/data/img/avatars/' . $user_id . '.jpg';

			$comments_text .= '<div class="comment" id="comment_' . $comment->id . '">';

				if (Auth::check() && $user_id == Auth::user()->id) {
					$comments_text .= '<div class="comment_controls">';
					$comments_text .= '<p  class="symlink" onclick="comment_edit(' . $comment->id . ')">Редактировать</p>';
					$comments_text .= ' | ';
					$comments_text .= '<p  class="symlink" onclick="comment_delete(' . $comment->id . ')">Удалить</p>';
					$comments_text .= '</div>';
				}
			
				$comments_text .= '<div class="comment_info">';
					$comments_text .= '<p><a href="/user/' . $user_id . '/profile">' . $comment->user->username . '</a><p>';
					$comments_text .= '<p class="comment_date">' . LocalizedCarbon::instance($comment->created_at)->diffForHumans() . ':</p>';
				$comments_text .= '</div>';

				$comments_text .= '<div class="comment_body">';
					$comments_text .= '<div class="comment_avatar">';
						if (file_exists($file_path)) {
							$comments_text .= '<a href="/user/' . $user_id . '/profile"><img src="/data/img/avatars/' . $user_id . '.jpg" alt=""/></a><br/>';
						}
					$comments_text .= '</div>';
					$comments_text .= '<div class="comment_text" id="comment_' . $comment->id . '_text">';
						$comments_text .= nl2br($comment->comment);
					$comments_text .= '</div>';
				$comments_text .= '</div>';

			$comments_text .= '</div>';

			if ($no_br) {
				$comments_text = preg_replace('/\n/', '', $comments_text);
				$comments_text = preg_replace('/"/', '\"', $comments_text);
			}
		}

		return $comments_text;
	}


	/**
	 * @param $section
	 * @return mixed
	 */
	public static function get_section_name($section)
	{
		$result = Section::where('alt_name', '=', $section)->value('name'); //->remember(60)
		return $result;
	}


	/**
	 * @param $section
	 * @return mixed
	 */
	public static function get_section_type($section)
	{
		$result = Section::where('alt_name', '=', $section)->value('type'); //->remember(60)
		return $result;
	}


	/**
	 * @param $section
	 * @return mixed
	 */
	public static function get_object_by($section)
	{
		$type = Helpers::get_section_type($section);
		$result = new $type;

		return $result;
	}

	/**
	 * @param $str
	 * @return string
	 */
	public static function mb_ucwords($str) {
		$str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
		return ($str);
	}

	/**
	 * @param $word
	 * @param bool $all2lower
	 * @return string
	 */
	public static function mb_ucfirst ($word, $all2lower = false)
	{
		if($all2lower) {
			return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word), 'UTF-8');
		} else {
			return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, mb_strlen($word), 'UTF-8');
		}
	}

	/**
	 * @return bool
	 */
	public static function is_admin() {

		$result = false;

		if(Auth::check()) {
			if('admin' == Auth::user()->roles()->first()->role) {
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	public static function is_moderator() {

		$result = false;

		if(Auth::check()) {
			$role = Auth::user()->roles()->first()->role;
			if('admin' == $role || 'moderator' == $role) {
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	public static function is_banned() {

		$result = false;

		if(Auth::check()) {
			if('banned' == Auth::user()->roles()->first()->role) {
				$result = true;
			}
		}

		return $result;
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
	public static function get_recommend($section) {

		$result = '';

		$type = Helpers::get_section_type($section);
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
				$result = Helpers::get_element($element, $section, '', true);
			}
		}

		return $result;

	}

	public static function get_fav_genres($user_id, $type) {

		$total_rates = 100;
		$max_rate = 8;
		$total_gens = 3;

		$minutes = 60*24;
		$var_name = 'fav_gens_'.$type.'_user_'.$user_id;

		$value = Cache::remember($var_name, $minutes, function() use ($user_id, $type, $total_rates, $max_rate, $total_gens)
		{
			$fav_els = Rate::where('user_id', '=', $user_id)
				->where('rate', '>', $max_rate)
				->where('element_type', '=', $type)
				->limit($total_rates)
				->pluck('element_id')
			;

			$fav_genres = ElementGenre::select(DB::raw('genre_id, count(`element_id`) as el_count'))
				->where('element_type', '=', $type)
				->whereIn('element_id', $fav_els)
				->groupBy('genre_id')
				->orderBy('el_count', 'desc')
				->limit($total_gens)
				->pluck('genre_id')
			;

			$fav_gen_names = Genre::where('element_type', '=', $type)
				->whereIn('id', $fav_genres)
				//->pluck('name')
				->get()
			;

			return $fav_gen_names;
		});

		return $value;

		//'<pre>'.print_r($fav_gen_names, true).'</pre>';

	}

	public static function switch2en($string) {

		$search = array(
			"й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
			"ф","ы","в","а","п","р","о","л","д","ж","э",
			"я","ч","с","м","и","т","ь","б","ю"
		);
		$replace = array(
			"q","w","e","r","t","y","u","i","o","p","[","]",
			"a","s","d","f","g","h","j","k","l",";","'",
			"z","x","c","v","b","n","m",",","."
		);
		//die(str_replace($search, $replace, $string));
		return str_replace($search, $replace, $string);

		//return $string;
	}

	public static function switch2ru($string) {

		$search = array(
			"q","w","e","r","t","y","u","i","o","p","[","]",
			"a","s","d","f","g","h","j","k","l",";","'",
			"z","x","c","v","b","n","m",",","."
		);
		$replace = array(
			"й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
			"ф","ы","в","а","п","р","о","л","д","ж","э",
			"я","ч","с","м","и","т","ь","б","ю"
		);
		//die(str_replace($search, $replace, $string));
		return str_replace($search, $replace, $string);

		//return $string;
	}


	/**
	 * @param $site
	 * @param $name
	 * @return string
	 */
	public static function get_ext_link($site, $name){

		$link = '';

		switch($site) {

			case 'kinopoisk':
				$protocol = 'http';
				$url = 'www.kinopoisk.ru/index.php';
				$query = 'first=yes&kp_query';
				$target = '_blank';
				$site_name = 'Кинопоиск';
				break;

			case 'wiki': // https://ru.wikipedia.org/w/index.php?search=евангелинон
				$protocol = 'https';
				$url = 'ru.wikipedia.org/w/index.php';
				$query = 'search';
				$target = '_blank';
				$site_name = 'Википедия';
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

			default:
				$protocol = 'http';
				$url = $site;
				$query = '';
				$target = '_blank';
				$site_name = 'Ссылка';
				break;

		}

		$link .= '<a href="'.$protocol.'://'.$url.'?'.$query.'='.urlencode($name).'" target="'.$target.'">'.$site_name.'</a>';

		return $link;

	}

	/**
	 * @return string
	 */
	public static function get_stats() {

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

		$stats .= '<p>'.Helpers::number($stats_array['books'], array('книга', 'книги', 'книг')).', ';
		$stats .= ''.Helpers::number($stats_array['films'], array('фильм', 'фильма', 'фильмов')).', ';
		$stats .= ''.Helpers::number($stats_array['games'], array('игра', 'игры', 'игр')).', ';
		$stats .= ''.Helpers::number($stats_array['albums'], array('альбом', 'альбома', 'альбомов')).'</p>';

		if(Helpers::is_admin()) {

			$stats .= '<p>'.Helpers::number($stats_array['users'], array('пользователь', 'пользователя', 'пользователей')).', ';
			$stats .= ''.Helpers::number($stats_array['rates'], array('оценка', 'оценки', 'оценок')).', ';
			$stats .= ''.Helpers::number($stats_array['comments'], array('комментарий', 'комментария', 'комментариев')).'</p>';

		}

		return $stats;
	}

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
