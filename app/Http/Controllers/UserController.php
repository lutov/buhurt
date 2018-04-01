<?php namespace App\Http\Controllers;

use App\Models\Helpers\SectionsHelper;
use DB;
use Illuminate\Http\Request;
use URL;
use Auth;
use View;
use Mail;
use Hash;
use Input;
use Session;
use Redirect;
use Validator;
use ResizeCrop;
use App\Models\User;
use App\Models\Rate;
use App\Models\Option;
use App\Models\Wanted;
use App\Models\Helpers;
use App\Models\Section;
use App\Models\Roleuser;
use App\Models\OptionUser;
use App\Models\Achievement;

class UserController extends Controller {

	/**
	 * User Form.
	 *
	 * @return Response
	 */
	public function index() {

		return View::make('user.index');

	}

	/**
	 * Registration form.
	 *
	 * @return Response
	 */
	public function register() {

		return View::make('user.register');

	}

	/**
	 * Registring new user and storing him to DB.
	 *
	 * @return Response
	 */
	public function store() {

		$rules = array(
			'email' 	=> 'required|email|unique:users,email',
			'password' 	=> 'required',
			'username'	=> 'required|unique:users,username',
			'g-recaptcha-response' => 'required|recaptcha',
		);

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {

			return Redirect::to(URL::action('UserController@register'))
				->withInput()
				->withErrors($validator)
				->with('message', 'При&nbsp;заполнении&nbsp;допущены&nbsp;ошибки')
			;

		}

		$user_name = strip_tags(Input::get('username'));
		$user_name = str_replace("'", "", $user_name);
		$user_name = str_replace("`", "", $user_name);
		$user_name = str_replace("*", "", $user_name);
		$user_name = str_replace("%", "", $user_name);

		if(empty($user_name)) {

			return Redirect::to(URL::action('UserController@register'))
				->withInput()
				->with('message', 'Имя&nbsp;пользователя&nbsp;содержит&nbsp;недопустимые&nbsp;символы');

		}

		$credentials = array(
			'email' => strip_tags(Input::get('email')),
			'password' => Input::get('password')
		);

		$user_ip = $_SERVER['REMOTE_ADDR'];
		$ip_table = 'ip2ruscity_ip_compact';
		$city_id = DB::table($ip_table)
			->whereRaw( "INET_ATON('".$user_ip."') BETWEEN `num_ip_start` AND `num_ip_end`" )
			//->first()
			//->remember(60)
			->value('city_id')
			//->toSql()
			//->get()
		;

		// Create user
		$user = new User;
		$user->email = $credentials['email'];
		$user->username = $user_name;
		$user->password = Hash::make($credentials['password']);
		if(!empty($city_id)) {$user->city_id = $city_id;}
		$user->save();

		$user_id = $user->id;

		// Add default role
		$roles = new Roleuser;
		$roles->role_id = 3; // user
		$roles->user_id = $user_id;
		$roles->save();

		// Log user in
		Auth::login($user);
		//return Redirect::to('user/profile/'.$user_id);

		$email = 'Здравствуйте, '.$user->username."\n\nТеперь Вы может пользоваться всеми возможностями системы: ставить оценки, комментировать произведения, зарабатывать достижения и настраивать свой профиль.";

		Mail::raw($email, function($message) use ($user)
		{
			$message->from('robot@free-buhurt.club', 'Бугурт');

			$message->to($user->email)->subject('Вы зарегистрировались в системе «Бугурт»');
		});

		return Redirect::to('/')->with('message', 'Добро&nbsp;пожаловать');
	}


	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function login(Request $request) {

		if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')), true) ||
			Auth::attempt(array('username' => Input::get('email'), 'password' => Input::get('password')), true)) {

			$request->session()->flush();

			return Redirect::back()->with('message', 'Добро&nbsp;пожаловать');

		}

		return Redirect::back()->withInput(Input::except('password'))->with('message', 'Неправильные&nbsp;данные,&nbsp;попробуйте&nbsp;еще&nbsp;раз');
	}


	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function logout(Request $request) {

		Auth::logout();

		$request->session()->flush();

		return Redirect::to('/')->with('message', 'До&nbsp;свидания');
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function view(Request $request, $id) {

		if(Auth::check() && $id == Auth::user()->id) {
			$user = Auth::user();
		} else {
			$user = User::find($id);
		}

		if(isset($user->id)) {
			$avatar = 0;
			$file_path = public_path() . '/data/img/avatars/' . $id . '.jpg';
			//die($file_path);
			if (file_exists($file_path)) {
				$avatar = $id;
			}

			$rates = new Rate();
			$wants = new Wanted();
			$option = new Option();
			$achievement = new Achievement();

			$books_rated = $rates
				->where('element_type', '=', 'Book')
				->where('user_id', '=', $user->id)
				->count('id');
			$films_rated = $rates
				->where('element_type', '=', 'Film')
				->where('user_id', '=', $user->id)
				->count('id');
			$games_rated = $rates
				->where('element_type', '=', 'Game')
				->where('user_id', '=', $user->id)
				->count('id');
			$albums_rated = $rates
				->where('element_type', '=', 'Album')
				->where('user_id', '=', $user->id)
				->count('id');

			$books_wanted = $wants
				->where('wanted', '=', 1)
				->where('element_type', '=', 'Book')
				->where('user_id', '=', $user->id)
				->count('id');
			$films_wanted = $wants
				->where('wanted', '=', 1)
				->where('element_type', '=', 'Film')
				->where('user_id', '=', $user->id)
				->count('id');
			$games_wanted = $wants
				->where('wanted', '=', 1)
				->where('element_type', '=', 'Game')
				->where('user_id', '=', $user->id)
				->count('id');
			$albums_wanted = $wants
				->where('wanted', '=', 1)
				->where('element_type', '=', 'Album')
				->where('user_id', '=', $user->id)
				->count('id');

			$books_not_wanted = $wants
				->where('not_wanted', '=', 1)
				->where('element_type', '=', 'Book')
				->where('user_id', '=', $user->id)
				->count('id');
			$films_not_wanted = $wants
				->where('not_wanted', '=', 1)
				->where('element_type', '=', 'Film')
				->where('user_id', '=', $user->id)
				->count('id');
			$games_not_wanted = $wants
				->where('not_wanted', '=', 1)
				->where('element_type', '=', 'Game')
				->where('user_id', '=', $user->id)
				->count('id');
			$albums_not_wanted = $wants
				->where('not_wanted', '=', 1)
				->where('element_type', '=', 'Album')
				->where('user_id', '=', $user->id)
				->count('id');

			$achievements = $achievement->all();
			$user_achievements = $user
				->achievements()
				->pluck('achievement_id')
				->toArray()
			;

			$options = $option->all();
			$user_options = $user
				->options()
				->where('enabled', '=', 1)
				->pluck('option_id')
				->toArray()
			;

			/*
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$ip_table = 'ip2ruscity_ip_compact';
			$city_id = DB::table($ip_table)
				->whereRaw( "INET_ATON('".$user_ip."') BETWEEN `num_ip_start` AND `num_ip_end`" )
				//->first()
				->value('city_id')
				//->toSql()
				//->get()
			;
			//die(print_r($city_id));
			//"SELECT * FROM `` WHERE INET_ATON('".$_SERVER['REMOTE_ADDR']."') BETWEEN `num_ip_start` AND `num_ip_end`";
			*/

			$city_table = 'ip2ruscity_cities';
			$city_id = $user->city_id;
			$city = DB::table($city_table)
				->where('city_id', '=', $city_id)
				//->remember(60)
				->first()
			;

			$fav_gens_books = Helpers::get_fav_genres($user->id, 'Book');
			$fav_gens_films = Helpers::get_fav_genres($user->id, 'Film');
			$fav_gens_games = Helpers::get_fav_genres($user->id, 'Game');
			$fav_gens_albums = Helpers::get_fav_genres($user->id, 'Album');
			
			$chart_rates = Rate::select(DB::raw('count(*) as rate_count, rate'))
				->where('user_id', '=', $user->id)
				->groupBy('rate')
				//->get()
				//->toArray()
				->pluck('rate_count')
				->toArray()
			;
			
			//die(print_r($chart_rates));

			return View::make('user.profile', array(
				'request' => $request,
				'user' => $user,
				'avatar' => $avatar,
				'books_rated' => $books_rated,
				'films_rated' => $films_rated,
				'games_rated' => $games_rated,
				'albums_rated' => $albums_rated,
				'books_wanted' => $books_wanted,
				'films_wanted' => $films_wanted,
				'games_wanted' => $games_wanted,
				'albums_wanted' => $albums_wanted,
				'books_not_wanted' => $books_not_wanted,
				'films_not_wanted' => $films_not_wanted,
				'games_not_wanted' => $games_not_wanted,
				'albums_not_wanted' => $albums_not_wanted,
				'achievements' => $achievements,
				'user_achievements' => $user_achievements,
				'options' => $options,
				'user_options' => $user_options,
				'city' => $city,
				'fav_gens_books' => $fav_gens_books,
				'fav_gens_films' => $fav_gens_films,
				'fav_gens_games' => $fav_gens_games,
				'fav_gens_albums' => $fav_gens_albums,
				'chart_rates' => $chart_rates,
			));
		}
		else {
			return Redirect::to('/');
		}
	}


	/**
	 * @param Request $request
	 * @param $id
	 * @param $section
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function rates(Request $request, $id, $section) {

		if(Auth::check() && $id == Auth::user()->id) {

			$user = Auth::user();

		} else {

			$user = User::find($id);

		}

		if(isset($user->id)) {

			$user_id = $user->id;

			$section_name = SectionsHelper::getSectionName($section);
			$type = SectionsHelper::getSectionType($section);

			$sort = Input::get('sort', 'rates.created_at');
			$sort_direction = Input::get('sort_direction', 'desc');
			$limit = 28;

			$sort_options = array(
				'rates.created_at' => 'Время выставления оценки',
				'rates.rate' => 'Оценка',
				$section.'.created_at' => 'Время добавления произведения',
				$section.'.name' => 'Название',
				$section.'.alt_name' => 'Оригинальное название',
				$section.'.year' => 'Год'
			);

			$elements = $type::select($section.'.*')
				->leftJoin('rates', $section.'.id', '=', 'rates.element_id')
				->where('rates.element_type', '=', $type)
				->where('rates.user_id', '=', $id)
				->with(array('rates' => function($query) use($user_id, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->orderBy($sort, $sort_direction)
				->paginate($limit)
			;

			return View::make('user.rates.index', array(
				'request' => $request,
				'user' => $user,
				'section' => $section,
				'section_name' => $section_name,
				'sort_options' => $sort_options,
				'elements' => $elements
			));
		} else {

			return Redirect::to('/');

		}
	}


	public function rates_export($id, $section) {

		if(Auth::check() && $id == Auth::user()->id) {

			$user = Auth::user();
			$user_id = $user->id;

			$type = SectionsHelper::getSectionType($section);

			$sort = Input::get('sort', 'rates.created_at');
			$sort_direction = Input::get('sort_direction', 'desc');

			$elements = $type::select($section.'.*')
				->leftJoin('rates', $section.'.id', '=', 'rates.element_id')
				->where('rates.element_type', '=', $type)
				->where('rates.user_id', '=', $id)
				->with(array('rates' => function($query) use($user_id, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->orderBy($sort, $sort_direction)
				->get()
			;

			$path = '/files/rates/user_'.$user_id.'-'.$section.'_rates.csv';
			$rates = '"Название";"Оригинальное название";"Год";"Оценка";"Время выставления оценки"'."\n";

			foreach($elements as $element) {

				$rates .= '"'.$element->name.'";';
				$rates .= '"'.$element->alt_name.'";';
				$rates .= '"'.$element->year.'";';
				$rates .= '"'.$element->rates[0]->rate.'";';
				$rates .= '"'.$element->rates[0]->created_at.'"';
				$rates .= "\n";

			}

			//echo '<pre>'.print_r($elements[0]->rates[0]->created_at, true).'</pre>';
			//echo '<pre>'.print_r($rates, true).'</pre>';

			file_put_contents(public_path().$path, $rates);

			return Redirect::to($path);

		}
		else {
			return Redirect::to('/');
		}
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @param $section
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function wanted(Request $request, $id, $section) {

		if(Auth::check() && $id == Auth::user()->id) {

			$user = Auth::user();
		}
		else {

			$user = User::find($id);

		}

		if(isset($user->id)) {

			//$section = $this->prefix;
			$get_section = Section::where('alt_name', '=', $section)->first();
			$ru_section = $get_section->name;
			$type = $get_section->type;

			$sort = Input::get('sort', $section.'.created_at');
			$sort_direction = Input::get('sort_direction', 'desc');
			$limit = 28;

			$sort_options = array(
				$section.'.created_at' => 'Время добавления',
				$section.'.name' => 'Название',
				$section.'.alt_name' => 'Оригинальное название',
				$section.'.year' => 'Год'
			);

			$user_id = $user->id;
			$wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			$elements = $type::orderBy($sort, $sort_direction)
				->with(array('rates' => function($query) use($user_id, $section, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->whereIn($section.'.id', $wanted)
				->paginate($limit)
			;

			return View::make('user.wanted.index', array(
				'request' => $request,
				'user' => $user,
				'section' => $section,
				'ru_section' => $ru_section,
				'sort_options' => $sort_options,
				'elements' => $elements
			));
		}
		else {
			return Redirect::to('/');
		}
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @param $section
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function not_wanted(Request $request, $id, $section) {

		if(Auth::check() && $id == Auth::user()->id) {

			$user = Auth::user();

		} else {

			$user = User::find($id);

		}

		if(isset($user->id)) {

			$get_section = Section::where('alt_name', '=', $section)->first();
			$ru_section = $get_section->name;
			$type = $get_section->type;

			$sort = Input::get('sort', $section.'.created_at');
			$sort_direction = Input::get('sort_direction', 'desc');
			$limit = 28;

			$sort_options = array(
				$section.'.created_at' => 'Время добавления',
				$section.'.name' => 'Название',
				$section.'.alt_name' => 'Оригинальное название',
				$section.'.year' => 'Год'
			);

			$user_id = $user->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			$elements = $type::orderBy($sort, $sort_direction)
				->with(array('rates' => function($query) use($user_id, $section, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->whereIn($section.'.id', $not_wanted)
				->paginate($limit)
			;

			return View::make('user.not_wanted.index', array(
				'request' => $request,
				'user' => $user,
				'section' => $section,
				'ru_section' => $section,
				'sort_options' => $sort_options,
				'elements' => $elements
			));
		}
		else {
			return Redirect::to('/');
		}
	}


	public function avatar() {

		if(Auth::check()) {

			$id = Auth::user()->id;
			//$avatar = Input::get('avatar');
			$path = public_path() . '/data/img/avatars/';
			//die($path);
			$fileName = $id.'.jpg';
			if (Input::hasFile('avatar')) {
				//Input::file('cover')->move($path, $fileName);
				$full_path = $path.'/'.$fileName;
				//die($full_path);
				$resize = ResizeCrop::resize(Input::file('avatar')->getRealPath(), $full_path, 200, 0);
			}
		}
		else
		{

		}
		return Redirect::back();
	}


	public function change_password() {

		if (Auth::check()) {
			//$id = Auth::user()->id;

			if(!empty($_POST)) {
				$rules = array(
					'old_password' => 'required',
					'new_password' => 'required',
				);

				$validator = Validator::make(Input::all(), $rules);

				if ($validator->fails()) {
					return Redirect::back()->withErrors($validator);
				} else {
					$old_password = Input::get('old_password', '');
					$new_password = Input::get('new_password', '');
					if (!empty($old_password)) {
						$user = Auth::user();
						$username = $user->username;
						$credentials = array(
							'username' => $username,
							'password' => Input::get('old_password')
						);

						if (Auth::attempt($credentials)) {
							if (!empty($new_password)) {
								$user->password = Hash::make($new_password);
								$user->save();
								return Redirect::to('/')->with('message', 'Пароль&nbsp;изменён');
							}
						} else {
							return Redirect::back()->withErrors(array('message' => 'Текущий&nbsp;пароль&nbsp;введён&nbsp;неправильно'));
						}
					}
					else
					{
						// если старый пароль пуст
					}
				}
			}
			else {
				return View::make('user.change_password', array(
					//'user' => $user,
					//'section' => $section,
					//'sort_options' => $sort_options,
					//'elements' => $elements
				));
			}
		} else {

		}
		//return Redirect::back();
	}

	
	public function vk_auth() {

		if (!Auth::check()) {

			$VK_APP_ID = env('VK_APP_ID', '');
			$VK_SECRET_CODE = env('VK_SECRET_CODE', '');
			$VK_REDIRECT_URL = env('VK_REDIRECT_URL', '');

			$code = Input::get('code', '');
			
			if(!empty($code)) {		 
				$vk_grand_url = "https://api.vk.com/oauth/access_token?client_id=".$VK_APP_ID
					."&client_secret=".$VK_SECRET_CODE
					."&code=".$_GET['code']
					."&redirect_uri=".$VK_REDIRECT_URL
				;
			 
				// отправляем запрос на получения access token
				// Авторизация как таковая
				//$resp = file_get_contents($vk_grand_url);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $vk_grand_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0); 
				$resp = curl_exec($ch);
				curl_close($ch);
				//die($resp);
				$data = json_decode($resp, true);
				//die('stop');
				//die('<pre>'.print_r($data, true).'</pre>');
				if(!isset($data['error'])) {
					
					$vk_access_token = $data['access_token'];
					$vk_uid = $data['user_id'];
					if(isset($data['email'])) {$vk_email = $data['email'];} else {$vk_email = 'robot@buhurt.ru';}
					//$vk_access_token = 'f594c1d8381d04fe101d4de1e77c96b19bd521097f723bc57087f713a675cd4867d0b03254f691e93f94e';
					//$vk_uid =  '11347340';
					
					// тут проверяем, если ид в базе. Если есть, авторизуем. Если нет - идем дальше	
					$user = User::where('vk_id', '=', $vk_uid)->first();
					if(!count($user))
					{
						// обращаемся к ВК Api, получаем имя, фамилию и ID пользователя вконтакте
						// метод users.get
						//$res = file_get_contents("https://api.vk.com/method/users.get?uids=".$vk_uid."&access_token=".$vk_access_token."&fields=uid,first_name,last_name,nickname,photo"); 
						
						$url="https://api.vk.com/method/users.get?uids=".$vk_uid."&access_token=".$vk_access_token."&fields=uid,first_name,last_name,nickname,photo";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HEADER, 0); 
						$res = curl_exec($ch);
						curl_close($ch);
						
						$data = json_decode($res, true);
						//die('<pre>'.print_r($data, true).'</pre>');
						$user_info = $data['response'][0];
						//echo '<pre>'.print_r($user_info, true).'</pre>';
					  
						/*			  
						Array
						(
							[uid] => 11347340
							[first_name] => Владислав
							[last_name] => Шевченко
							[nickname] => 
							[photo] => http://cs618031.vk.me/v618031340/b9ef/h9Vts5jyTl8.jpg
						)			  
						*/
						//echo $user_info['first_name']." ".$user_info['last_name']."";
						//echo "<img src='".$user_info['photo']."' border='0' />";

						$user_ip = $_SERVER['REMOTE_ADDR'];
						$ip_table = 'ip2ruscity_ip_compact';
						$city_id = DB::table($ip_table)
							->whereRaw( "INET_ATON('".$user_ip."') BETWEEN `num_ip_start` AND `num_ip_end`" )
							//->first()
							//->remember(60)
							->value('city_id')
							//->toSql()
							//->get()
						;
						
						$new_user = new User();
						$new_user->vk_id = (int) $vk_uid;					
						$new_user->username = trim($user_info['first_name']." ".$user_info['last_name']);
						$new_user->email = $vk_email;
							$gen_pass = substr(base64_encode(mt_rand().$vk_uid), 0, 12);
						$new_user->password = Hash::make($gen_pass);
						if(!empty($city_id)) {$new_user->city_id = $city_id;}
						$new_user->save();
						
						// Add default role
						$roles = new Roleuser;
						$roles->role_id = 3; // user
						$roles->user_id = $new_user->id;
						$roles->save();

						$email = 'Здравствуйте, '.$new_user->username."\n\nВаш пароль сгенерирован автоматически: ".$gen_pass."\n\nC ним Вы можете авторизоваться в системе не только через соцсеть, но и непосредственно в форме входа. При желании Вы можете также сменить пароль в своем профиле.\n\nТеперь Вы может пользоваться всеми возможностями системы: ставить оценки, комментировать произведения, зарабатывать достижения и настраивать свой профиль.";

						Mail::raw($email, function($message) use ($new_user)
						{
							$message->from('robot@buhurt.ru', 'Бугурт');

							$message->to($new_user->email)->subject('Вы зарегистрировались в системе «Бугурт»');
						});
						
						Auth::login($new_user);

						return Redirect::to('/')->with('message', 'Вы&nbsp;зарегистрированы');
					} else {
						Auth::login($user);
						return Redirect::to('/')->with('message', 'Вы&nbsp;успешно&nbsp;авторизовались');
					}
				} else {
					
					return Redirect::to('/')->with('message', 'Авторизоваться&nbsp;не&nbsp;удалось. Причина: '.$data['error'].' — '.$data['error_description']);
					
				}
			} else {
				return Redirect::to('/')->with('message', 'Авторизоваться&nbsp;не&nbsp;удалось');
			}

			/*
				<p>
					<span class="symlink"
					onclick="window.open('https://oauth.vk.com/access_token?client_id=&client_secret=&code=&redirect_uri=http://www.free-buhurt.club/user/vk_auth ');">
						Токен
					</span>
				</p>

				<!-- code=7739f49d6af04d4d9d -->

				<!--

				https://oauth.vk.com/access_token?
				client_id=&
				client_secret=
				code=&
				redirect_uri=http://www.free-buhurt.club/user/vk_auth 

				-->
				 
				{"access_token":"","expires_in":,"user_id":,"email":"i@vlad-lutov.name"}
			*/		
		}
		else
		{
			return Redirect::to('/');
		}
	}

	public function options($id) {

		if(Auth::check() && $id == Auth::user()->id) {

			$user = Auth::user();

			$user_options = $user
				->options()
				->pluck('option_id')
				->toArray()
			;

			//print_r(Input::all());
			//print_r($user_options);
			//[private_my_comments] => 1 [private_other_comments] => 1

			//$user = User::find($id);

			$post_options['private_my_comments'] = Input::get('private_my_comments', 0);
			$post_options['private_other_comments'] = Input::get('private_other_comments', 0);

			$options = Option::all();

			foreach($options as $option) {

				//die($option->name);
				$tmp_name = $option->name;
				if(isset($post_options[$tmp_name])) { //&& 0 != $$option->name

					//echo '<h1>'.$option->id.'</h1>';
					//$user_options = [1 => '1', 2 => '2'];
					$option_id = $option->id;
					$is_enabled = in_array($option_id, $user_options);

					if($is_enabled) {

						$new_option = OptionUser::where('option_id', '=', $option->id)
							->first()
						;

					} else {

						$new_option = new OptionUser;

					}

					//die('<pre>'.print_r($user_options, true).'</pre>'.$new_option);

					$new_option->user_id = $user->id;
					$new_option->option_id = $option->id;
					$new_option->enabled = $post_options[$tmp_name];
					$new_option->save();


					//echo $tmp_name.' - '.$post_options[$tmp_name].'<br>';
				} else {
					//echo 'no '.$tmp_name.'<br>';
				}
			}

			return Redirect::to('/user/'.$user->id.'/profile')->with('message', 'Настройки&nbsp;сохранены');

		} else {

			return Redirect::to('/')->with('message', 'Нет&nbsp;прав&nbsp;доступа');

		}
	}
}