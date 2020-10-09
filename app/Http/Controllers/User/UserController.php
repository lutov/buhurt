<?php namespace App\Http\Controllers\User;

use App\Helpers\DebugHelper;
use App\Helpers\ElementsHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Data\Collection;
use App\Models\User\Event;
use App\Helpers\RolesHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Models\User\Unwanted;
use App\Models\User\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use ResizeCrop;
use App\Models\User\Rate;
use App\Models\User\Option;
use App\Models\User\Wanted;
use App\Models\Data\Section;
use App\Models\User\Roleuser;
use App\Models\Search\OptionUser;
use App\Models\User\Achievement;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class UserController extends Controller {

	private $prefix = 'users';

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function index(Request $request) {
		if(Auth::check()) {
			return Redirect::to('/');
		} else {
			return view('sections.user.login', array(
				'request' => $request
			));
		}
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function register(Request $request) {

		//Cache::flush();

		if(Auth::check()) {

			return Redirect::to('/');

		} else {

			return View::make('sections.user.register', array(
				'request' => $request
			));

		}

	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function store(Request $request) {

		$rules = array(
			'email' 	=> 'required|email|unique:users,email',
			'password' 	=> 'required',
			'username'	=> 'required|unique:users,username',
			//'g-recaptcha-response' => 'required|recaptcha',
            'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule('user_register_captcha_action')]
		);

		$validator = Validator::make($request->all(), $rules);

		if($validator->fails()) {

			return Redirect::to(URL::action('User\UserController@register'))
				->withInput()
				->withErrors($validator)
				->with('message', 'При&nbsp;заполнении&nbsp;допущены&nbsp;ошибки')
			;

		}

		$user_name = strip_tags($request->get('username'));
		$user_name = str_replace("'", "", $user_name);
		$user_name = str_replace("`", "", $user_name);
		$user_name = str_replace("*", "", $user_name);
		$user_name = str_replace("%", "", $user_name);

		if(empty($user_name)) {
			return Redirect::to(URL::action('User\UserController@register'))
				->withInput()
				->with('message', 'Имя&nbsp;пользователя&nbsp;содержит&nbsp;недопустимые&nbsp;символы');
		}

		$credentials = array(
			'email' => strip_tags($request->get('email')),
			'password' => $request->get('password')
		);

		$user_ip = $_SERVER['REMOTE_ADDR'];
		$ip_table = 'ip2ruscity_ip_compact';
		$city_id = DB::table($ip_table)
			->whereRaw( "INET_ATON('".$user_ip."') BETWEEN `num_ip_start` AND `num_ip_end`" )
			->value('city_id')
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

		Mail::raw($email, function($message) use ($user) {
			$message->from('robot@buhurt.ru', 'Бугурт');

			$message->to($user->email)->subject('Вы зарегистрировались в системе «Бугурт»');
		});

		$event = new Event();
		$event->event_type = 'New';
		$event->element_type = 'User';
		$event->element_id = $user_id;
		$event->user_id = $user_id;
		$event->name = $user_name; //.' зарегистрирован';
		$event->text = 'Зарегистрирован';
		$event->save();

		return Redirect::to('/')->with('message', 'Добро&nbsp;пожаловать');
	}


	/**
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function login(Request $request) {

		if (Auth::attempt(array('email' => $request->get('email'), 'password' => $request->get('password')), true) ||
			Auth::attempt(array('username' => $request->get('email'), 'password' => $request->get('password')), true)) {

			$request->session()->flush();

			return Redirect::back()->with('message', 'Добро&nbsp;пожаловать');

		}

		return Redirect::back()->withInput($request->except('password'))->with('message', 'Неправильные&nbsp;данные,&nbsp;попробуйте&nbsp;еще&nbsp;раз');
	}


	/**
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function logout(Request $request) {

		Auth::logout();

		$request->session()->flush();

		return Redirect::to('/')->with('message', 'До&nbsp;свидания');
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function profile(Request $request, $id) {

		//Cache::flush();
		if(Auth::check() && $id == Auth::user()->id) {
			$user = Auth::user();
		} else {
			$user = User::find($id);
		}

		if(isset($user->id)) {

			$avatar = 0;
			$hash = '';

			$file_path = public_path() . '/data/img/avatars/' . $id . '.jpg';
			//die($file_path);

			if (file_exists($file_path)) {
				$avatar = $id;
				$hash = md5_file($file_path);
			}

			$rates = new Rate();
			$wanted = new Wanted();
			$unwanted = new Unwanted();
			$option = new Option();
			$achievement = new Achievement();

			$books_rated = $rates
				->where('element_type', '=', 'Book')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$films_rated = $rates
				->where('element_type', '=', 'Film')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$games_rated = $rates
				->where('element_type', '=', 'Game')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$albums_rated = $rates
				->where('element_type', '=', 'Album')
				->where('user_id', '=', $user->id)
				->count('id')
			;

			$books_wanted = $wanted
				->where('element_type', '=', 'Book')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$films_wanted = $wanted
				->where('element_type', '=', 'Film')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$games_wanted = $wanted
				->where('element_type', '=', 'Game')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$albums_wanted = $wanted
				->where('element_type', '=', 'Album')
				->where('user_id', '=', $user->id)
				->count('id')
			;

			$books_unwanted = $unwanted
				->where('element_type', '=', 'Book')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$films_unwanted = $unwanted
				->where('element_type', '=', 'Film')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$games_unwanted = $unwanted
				->where('element_type', '=', 'Game')
				->where('user_id', '=', $user->id)
				->count('id')
			;
			$albums_unwanted = $unwanted
				->where('element_type', '=', 'Album')
				->where('user_id', '=', $user->id)
				->count('id')
			;

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

			$city_table = 'ip2ruscity_cities';
			$city_id = $user->city_id;
			$city = DB::table($city_table)
				->where('city_id', '=', $city_id)
				//->remember(60)
				->first()
			;

			$fav_gens_books = UserHelper::getFavGenresNames($user->id, 'Book');
			$fav_gens_films = UserHelper::getFavGenresNames($user->id, 'Film');
			$fav_gens_games = UserHelper::getFavGenresNames($user->id, 'Game');
			$fav_gens_albums = UserHelper::getFavGenresNames($user->id, 'Album');
			
			$chart_rates = Rate::select(DB::raw('count(*) as rate_count, rate'))
				->where('user_id', '=', $user->id)
				->groupBy('rate')
				//->get()
				//->toArray()
				->pluck('rate_count')
				->toArray()
			;
			
			//die(print_r($chart_rates));

            $has_genres = count($fav_gens_books) || count($fav_gens_films) || count($fav_gens_games) || count($fav_gens_albums);
            $has_rates = (!empty($chart_rates));
            $has_achievements = true;
            $has_options = (Auth::check() && Auth::user()->id == $user->id);

            $tabs = array();
            $tabs['info'] = ElementsHelper::tab(
                'info',
                'Информация',
                0,
                new Section(),
                new Collection()
            );
            if($has_genres) {
                $tabs['genres'] = ElementsHelper::tab(
                    'genres',
                    'Жанры',
                    0,
                    new Section(),
                    new Collection()
                );
            }
            if($has_rates) {
                $tabs['rates'] = ElementsHelper::tab(
                    'rates',
                    'Оценки',
                    0,
                    new Section(),
                    new Collection()
                );
            }
            if($has_achievements) {
                $tabs['achievements'] = ElementsHelper::tab(
                    'achievements',
                    'Достижения',
                    0,
                    new Section(),
                    new Collection()
                );
            }
            if($has_options) {
                $tabs['options'] = ElementsHelper::tab(
                    'options',
                    'Настройки',
                    0,
                    new Section(),
                    new Collection()
                );
            }

			return View::make('sections.user.profile', array(
				'request' => $request,
				'user' => $user,
				'avatar' => $avatar,
				'hash' => $hash,
				'books_rated' => $books_rated,
				'films_rated' => $films_rated,
				'games_rated' => $games_rated,
				'albums_rated' => $albums_rated,
				'books_wanted' => $books_wanted,
				'films_wanted' => $films_wanted,
				'games_wanted' => $games_wanted,
				'albums_wanted' => $albums_wanted,
				'books_unwanted' => $books_unwanted,
				'films_unwanted' => $films_unwanted,
				'games_unwanted' => $games_unwanted,
				'albums_unwanted' => $albums_unwanted,
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
                'tabs' => $tabs,
                'has_genres' => $has_genres,
                'has_rates' => $has_rates,
                'has_achievements' => $has_achievements,
                'has_options' => $has_options,
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
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
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

			$sort = $request->get('sort', 'rates.created_at');
			$order = $request->get('order', 'desc');
			$limit = 28;

			$sort = TextHelper::checkSort($sort);
			$order = TextHelper::checkOrder($order);

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
				->orderBy($sort, $order)
				->paginate($limit)
			;

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_options' => $sort_options,
				'sort' => $sort,
				'order' => $order,
			);

			return View::make('sections.user.rates.section', array(
				'request' => $request,
				'user' => $user,
				'section' => SectionsHelper::getSection($section),
				'section_name' => $section_name,
				'options' => $options,
				'elements' => $elements
			));

		} else {

			return Redirect::to('/');

		}
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @param $section
	 * @return mixed
	 */
	public function rates_export(Request $request, $id, $section) {

		if(Auth::check() && $id == Auth::user()->id) {

			$user = Auth::user();
			$user_id = $user->id;

			$type = SectionsHelper::getSectionType($section);

			$sort = $request->get('sort', 'rates.created_at');
			$order = $request->get('order', 'desc');

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
				->orderBy($sort, $order)
				->get()
			;

			$path = '/files/rates/user_'.$user_id.'-'.$section.'_rates.csv';
			$rates = '"Название";"Оригинальное название";"Год";"Оценка";"Время выставления оценки"'."\n";

			foreach($elements as $element) {

				$rates .= '"'.$element->name.'";';
				$rates .= '"'.implode('; ', $element->alt_name).'";';
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
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function wanted(Request $request, $id, $section) {

		if(Auth::check() && $id == Auth::user()->id) {
			$user = Auth::user();
		} else {
			$user = User::find($id);
		}

		if(isset($user->id)) {

			$ru_section = SectionsHelper::getSectionName($section);
			$type = SectionsHelper::getSectionType($section);

			$sort = $request->get('sort','created_at');
			$order = $request->get('order', 'desc');
			$limit = 28;

			$sort_options = array(
				'wanted.created_at' => 'Время добавления',
				$section.'.name' => 'Название',
				$section.'.alt_name' => 'Оригинальное название',
				$section.'.year' => 'Год'
			);

            $elements = $type::select($section.'.*')
                ->leftJoin('wanted', $section.'.id', '=', 'wanted.element_id')
                ->where('wanted.element_type', '=', $type)
                ->where('wanted.user_id', '=', $id)
                ->with(array('wanted' => function($query) use($user, $type)
                       {
                           $query
                               ->where('user_id', '=', $user->id)
                               ->where('element_type', '=', $type)
                           ;
                       })
                )
                ->orderBy($sort, $order)
                ->paginate($limit)
            ;

            $options = array(
                'header' => true,
                'footer' => true,
                'paginate' => true,
                'sort_options' => $sort_options,
                'sort' => $sort,
                'order' => $order,
            );

			return View::make('sections.user.wanted.section', array(
				'request' => $request,
				'user' => $user,
				'section' => SectionsHelper::getSection($section),
				'options' => $options,
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
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function unwanted(Request $request, $id, $section) {

		if(Auth::check() && $id == Auth::user()->id) {
			$user = Auth::user();
		} else {
			$user = User::find($id);
		}

		if(isset($user->id)) {

            $ru_section = SectionsHelper::getSectionName($section);
            $type = SectionsHelper::getSectionType($section);

			$sort = $request->get('sort', 'created_at');
			$order = $request->get('order', 'desc');
			$limit = 28;

			$sort_options = array(
				'unwanted.created_at' => 'Время добавления',
				$section.'.name' => 'Название',
				$section.'.alt_name' => 'Оригинальное название',
				$section.'.year' => 'Год'
			);

            $options = array(
                'header' => true,
                'footer' => true,
                'paginate' => true,
                'sort_options' => $sort_options,
                'sort' => $sort,
                'order' => $order,
            );

            $elements = $type::select($section.'.*')
                ->leftJoin('unwanted', $section.'.id', '=', 'unwanted.element_id')
                ->where('unwanted.element_type', '=', $type)
                ->where('unwanted.user_id', '=', $id)
                ->with(array('unwanted' => function($query) use($user, $type)
                       {
                           $query
                               ->where('user_id', '=', $user->id)
                               ->where('element_type', '=', $type)
                           ;
                       })
                )
                ->orderBy($sort, $order)
                ->paginate($limit)
            ;

			return View::make('sections.user.unwanted.section', array(
				'request' => $request,
				'user' => $user,
				'section' => SectionsHelper::getSection($section),
				'options' => $options,
				'elements' => $elements
			));
		}
		else {
			return Redirect::to('/');
		}
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function avatar(Request $request) {

		if(Auth::check()) {

			$id = Auth::user()->id;
			//$avatar = $request->get('avatar');
			$path = public_path() . '/data/img/avatars/';
			//die($path);
			$fileName = $id.'.jpg';
			if ($request->hasFile('avatar')) {
				//$request->file('cover')->move($path, $fileName);
				$full_path = $path.$fileName;
				//die($full_path);
				$real_path = $request->file('avatar')->getRealPath();
				//die($real_path);
				$resize = ResizeCrop::resize($real_path, $full_path, 370, 0);
			}
		} else {

		}
		return Redirect::back();
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function change_password(Request $request) {

		if (Auth::check()) {

			//$id = Auth::user()->id;

			if(!empty($_POST)) {
				$rules = array(
					'old_password' => 'required',
					'new_password' => 'required',
				);

				$validator = Validator::make($request->all(), $rules);

				if ($validator->fails()) {

					return Redirect::back()->withErrors($validator);

				} else {

					$old_password = $request->get('old_password', '');
					$new_password = $request->get('new_password', '');

					if (!empty($old_password)) {

						$user = Auth::user();
						$username = $user->username;
						$credentials = array(
							'username' => $username,
							'password' => $request->get('old_password')
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

					} else {

						// если старый пароль пуст
						return Redirect::to('/')->with('message', 'Не введён старый пароль');

					}

					return Redirect::to('/');

				}

			} else {

				return View::make('sections.user.change_password', array(
					'request' => $request,
				));

			}

		} else {

			return Redirect::to('/');

		}

	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function vk_auth(Request $request) {

		if (!Auth::check()) {

			$VK_APP_ID = env('VK_APP_ID', '');
			$VK_SECRET_CODE = env('VK_SECRET_CODE', '');
			$VK_REDIRECT_URL = env('VK_REDIRECT_URL', '');

			$code = $request->get('code', '');
			
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
					
					// тут проверяем, если ид в базе. Если есть, авторизуем. Если нет - идем дальше	
					$user = User::where('vk_id', '=', $vk_uid)->first();
					if(!isset($user->id)) {
						// обращаемся к ВК Api, получаем имя, фамилию и ID пользователя вконтакте
						// метод users.get

						$url="https://api.vk.com/method/users.get?v=5.92&user_ids=".$vk_uid."&access_token=".$vk_access_token."&fields=uid,first_name,last_name,nickname,photo_max";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HEADER, 0); 
						$res = curl_exec($ch);
						curl_close($ch);
						
						$data = json_decode($res, true);
						//echo DebugHelper::dump($data, 1); die();
						$user_info = $data['response'][0];
						//echo '<pre>'.print_r($user_info, true).'</pre>';

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

						if(isset($user_info['photo_max'])) {
							$path = public_path() . '/data/img/avatars/';
							$fileName = $new_user->id . '.jpg';
							$full_path = $path . $fileName;
							$real_path = $user_info['photo_max'];
							//die($real_path);
							$resize = ResizeCrop::resize($real_path, $full_path, 370, 0);
						}

						$email = 'Здравствуйте, '.$new_user->username."\n\nВаш пароль сгенерирован автоматически: ".$gen_pass."\n\nC ним Вы можете авторизоваться в системе не только через соцсеть, но и непосредственно в форме входа. При желании Вы можете также сменить пароль в своем профиле.\n\nТеперь Вы может пользоваться всеми возможностями системы: ставить оценки, комментировать произведения, зарабатывать достижения и настраивать свой профиль.";

						Mail::raw($email, function($message) use ($new_user) {

							$message->from('robot@buhurt.ru', 'Бугурт');

							$message->to($new_user->email)->subject('Вы зарегистрировались в системе «Бугурт»');

						});

                        $event = new Event();
                        $event->event_type = 'New';
                        $event->element_type = 'User';
                        $event->element_id = $new_user->id;
                        $event->user_id = $new_user->id;
                        $event->name = $new_user->username; //.' зарегистрирован';
                        $event->text = 'Зарегистрирован через <a href="https://vk.com">vk.com</a>';
                        $event->save();
						
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

		} else {

			return Redirect::to('/');

		}

	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return mixed
	 */
	public function options(Request $request, $id) {

		if(Auth::check() && $id == Auth::user()->id) {

			$user = Auth::user();

			$user_options = $user
				->options()
				->pluck('option_id')
				->toArray()
			;

			$post_options['private_my_comments'] = $request->get('private_my_comments', 0);
			$post_options['private_other_comments'] = $request->get('private_other_comments', 0);

			$options = Option::all();

			foreach($options as $option) {

				$tmp_name = $option->name;
				if(isset($post_options[$tmp_name])) {

					$option_id = $option->id;
					$is_enabled = in_array($option_id, $user_options);

					if($is_enabled) {

						$new_option = OptionUser::where('option_id', '=', $option->id)
							->first()
						;

					} else {

						$new_option = new OptionUser;

					}

					$new_option->user_id = $user->id;
					$new_option->option_id = $option->id;
					$new_option->enabled = $post_options[$tmp_name];
					$new_option->save();

				} else {



				}
			}

			return Redirect::to('/user/'.$user->id.'/profile')->with('message', 'Настройки&nbsp;сохранены');

		} else {

			return Redirect::to('/')->with('message', 'Нет&nbsp;прав&nbsp;доступа');

		}
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function list(Request $request) {

		if(RolesHelper::isAdmin($request)) {

			$section = $this->prefix;
			$ru_section = 'Пользователи';

			$sort = $request->get('sort', 'created_at');
			$order = $request->get('order', 'desc');
			$limit = 28;

			$elements = User::select('id', 'username as name')
				->orderBy($sort, $order)
				->paginate($limit)
			;

			return View::make('sections.user.list', array(
				'request' => $request,
				'elements' => $elements,
				'section' => 'user',
				'ru_section' => $ru_section,
			));

		} else {

			return Redirect::to('/');

		}

	}

}