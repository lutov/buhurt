<?php namespace App\Http\Controllers;

use DB;
use Auth;
use Cache;
use App\Models\Achievement;
use App\Models\AchievementUser;

class AchievementsController extends Controller {

	public function check() {

		$result = '{"msg_type":"message", "message":"Проверка&nbsp;достижений", "msg_img":[]}';

        $need_update = Cache::get('need_update', true);

		if(Auth::check() && $need_update)
		{
			$id = Auth::user()->id;
			$obtained = DB::table('achievements_users')->where('user_id', '=', $id)->pluck('achievement_id'); //->remember(60)
			$achievements = Achievement::whereNotIn('id', $obtained)->get(); // ->remember(60)
			$new = array();

			foreach($achievements as $key => $achievement)
			{
				$alt_name = $achievement->alt_name;
				$check = $this->$alt_name($id);
				//echo $check."\n";
				if($check)
				{
					$new[] = $achievement->id;
				}
				else
				{
					//echo $alt_name.' does not obtained'."\n";
				}
			}

			if(0 != count($new))
			{
				// тут сделать добавление ачивок
				foreach($new as $key => $value)
				{
					$new_achievement = new AchievementUser();
					$new_achievement->user_id = $id;
					$new_achievement->achievement_id = $value;
					$new_achievement->save();
				}
				$result = '{"msg_type":"achievement", "message":"Новое&nbsp;достижение", "msg_img":['.implode(', ', $new).']}';
			}

            $minutes = 10;
            Cache::put('need_update', false, $minutes);

		}

		//echo print_r($result, true);
		//$result = $achievements;
		//return print_r($result, true);
		return $result;
	}

	private function chain_of_events($user_id) {

		// Вы зарегистрировались на сайте, добавили произведение в список желаемого, поставили оценку и оставили комментарий
		$result = false;

		$total_comments = DB::table('comments')
			->select('id')
			->where('user_id', '=', $user_id)
			->limit(1)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_rates = DB::table('rates')
			->select('id')
			->where('user_id', '=', $user_id)
			->limit(1)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_wants = DB::table('wanted')
			->select('id')
			->where('user_id', '=', $user_id)
			->limit(1)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		if(0 != $total_rates && 0 != $total_comments && 0 != $total_wants) {$result = true;} else {/*echo $total_comments;*/}

		return $result;
	}

	private function socialize_it($user_id)
	{
		// Вы поставили 10 оценок и оставили 10 комментариев
		$result = false;

		$total_comments = DB::table('comments')
			->select('id')
			->where('user_id', '=', $user_id)
			->groupBy('id')
			->limit(11)
			//->remember(60)
			->count()
		;

		$total_rates = DB::table('rates')
			->select('id')
			->where('user_id', '=', $user_id)
			->groupBy('id')
			->limit(11)
			//->remember(60)
			->count()
		;

		if(10 <= $total_rates && 10 <= $total_comments) {$result = true;} else {/*echo $total_comments;*/}

		return $result;
	}


	private function alice($user_id)
	{
		// Вы оценили 1 игру, 1 книгу и 1 фильм об Алисе
		$result = false;

		$books = array(1339, 9943);
		$films = array(3623, 4593, 5516, 7233, 5959, 4663);
		$games = array(1348, 3012);

		$books_rates = DB::table('rates')
			->select('rate')
			->where('user_id', '=', $user_id)
			->where('element_type', '=', 'Book')
			->whereIn('element_id', $books)
			->groupBy('rate')
			->limit(1)
			//->remember(60)
			//->toSql()
			->count()
		;
		//echo $books_rates;

		$films_rates = DB::table('rates')
			->select('rate')
			->where('user_id', '=', $user_id)
			->where('element_type', '=', 'Film')
			->whereIn('element_id', $films)
			->groupBy('rate')
			->limit(1)
			//->remember(60)
			->count()
		;

		$games_rates = DB::table('rates')
			->select('rate')
			->where('user_id', '=', $user_id)
			->where('element_type', '=', 'Game')
			->whereIn('element_id', $games)
			->groupBy('rate')
			->limit(1)
			//->remember(60)
			->count()
		;

		if(0 != $books_rates && 0 != $films_rates && 0 != $games_rates) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function the_point_of_synthesis($user_id)
	{
		// Вы оценили более 25 ролевых игр и оценили Mass Effect 3 более чем в 8 баллов
		$result = false;

		$rpg = array(52, 60);

		$rpg_rated = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $rpg)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(26)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		$me_id = 6895;
		$mass_effect = DB::table('rates')
			->select('rate')
			->where('user_id', '=', $user_id)
			->where('element_type', '=', 'Game')
			->where('element_id', '=', $me_id)
			->limit(1)
			//->remember(60)
			->value('rate')
		;

		if(25 <= $rpg_rated && 8 <= $mass_effect) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function bro_machine($user_id) {

		// Вы оценили 10 фантастических произведений и 10 мюзиклов
		$result = false;

		$fantastic = array(7, 47);
		$music = array(36);

		//$rated_genres = array();
		$fantastic_rated = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $fantastic)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(11)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;

		$music_rated = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $music)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(11)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(10 <= $fantastic_rated && 10 <= $music_rated) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function commissar_tools($user_id)
	{
		// Вы отключили показ чужих комментариев, оставив свои публичными
		$result = false;

        $option_mine = 1;
        $option_their = 2;

        $mine = DB::table('options_users')
            ->where('user_id', '=', $user_id)
            ->where('option_id', '=', $option_mine)
            ->where('enabled', '=', 1)
			->groupBy('id')
            ->limit(1)
            ->count()
        ;
        $their = DB::table('options_users')
            ->where('user_id', '=', $user_id)
            ->where('option_id', '=', $option_their)
            ->where('enabled', '=', 1)
			->groupBy('id')
            ->limit(1)
            ->count()
        ;

        if(!$mine && $their) {
            $result = true;
        }

		return $result;
	}


	private function after_us($user_id)
	{
		// Вы оценили 20 произведений в одной коллекции
		$result = false;

		// :TODO: сделать сами коллекции

		return $result;
	}


	private function crew($user_id)
	{
		// Вы оценили 20 произведений для детей или 30 классических произведений
		$result = false;

		$classic = array(9);
		$kids = array(10);

		//$rated_genres = array();
		$classic_rated = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $classic)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(31)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;

		$kids_rated = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $kids)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(21)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(20 <= $kids_rated || 30 <= $classic_rated) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function flying_knife($user_id)
	{
		// Вы оценили 20 книг и 20 фильмов детективного жанра
		$result = false;

		$books = array(3);
		$films = array(23);

		//$rated_genres = array();
		$books_rated = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $books)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(21)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;

		$films_rated = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $films)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(21)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(20 <= $books_rated && 20 <= $films_rated) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function void($user_id)
	{
		// Вы оценили 20 классических фильмов в стиле киберпанка и 5 фильмов жанре артхаус
		$result = false;

		$cyberpunk = array(4670, 4955, 5393, 2961, 3083, 6938, 3575, 5171, 6144, 6485, 4172, 1407, 5487, 6906, 2625, 3930, 4815, 222, 5401, 6194, 673, 6891, 4553, 2241, 6494, 5308, 751);
		$arthouse = array(18);

		//$rated_genres = array();
		$rated_arthouse = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $arthouse)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(6)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;

		$rated_cyberpunk = DB::table('rates')
			->select('id')
			->where('user_id', '=', $user_id)
			->where('element_type', '=', 'Film')
			->whereIn('element_id', $cyberpunk)
			->limit(21)
			//->remember(60)
			->groupBy('id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(5 <= $rated_arthouse && 20 <= $rated_cyberpunk) {$result = true;} else {/*echo $rated_arthouse.' - '.$rated_cyberpunk;*/}

		return $result;
	}


	private function chauvinist_pig($user_id)
	{
		// Вы оценили 50 боевиков и ни одной мелодрамы
		$result = false;

		$war_genres = array(3, 20);
		$love_genres = array(4, 31);

		//$rated_genres = array();
		$rated_war_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $war_genres)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(51)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;

		$rated_love_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $love_genres)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(51)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(50 <= $rated_war_genres && 0 == $rated_love_genres) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function red_roses($user_id)
	{
		// Вы оценили 50 мелодрам и ни одного боевика
		$result = false;

		$war_genres = array(3, 20);
		$love_genres = array(4, 31);

		//$rated_genres = array();
		$rated_war_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $war_genres)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(51)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;

		$rated_love_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $love_genres)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(51)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(0 == $rated_war_genres && 50 <= $rated_love_genres) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function icecream($user_id)
	{
		// Вы оценили 50 мелодрам и 50 боевиков
		$result = false;

		$war_genres = array(3, 20);
		$love_genres = array(4, 31);

		//$rated_genres = array();
		$rated_war_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $war_genres)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(51)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;

		$rated_love_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $love_genres)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(51)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(50 <= $rated_war_genres && 50 <= $rated_love_genres) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function theory_of_masses($user_id)
	{
		// Вы оценили 50 фильмов с элементами эротики
		$result = false;

		$erotic = array(50, 66);

		//$rated_genres = array();
		$rated_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $erotic)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(51)
			//->remember(60)
			->groupBy('elements_genres.genre_id')
			->count()
			//->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(50 <= $rated_genres) {$result = true;} else {/*echo $rated_genres;*/}

		return $result;
	}


	private function last_resort($user_id)
	{
		// Вы оценили 50 отечественных фильмов
		$result = false;

		$countries = array(18, 19, 22);

		$rated_films = DB::table('rates')
			->select('rate')
			->leftJoin('countries_films', 'rates.element_id', '=', 'countries_films.film_id')
			->where('rates.element_type', '=', 'Film')
			->where('rates.user_id', '=', $user_id)
			->whereIn('countries_films.country_id', $countries)
			->limit(51)
			//->remember(60)
			//->toSql()
			->groupBy('rate')
			->count()
		;
		//echo $rated_films;

		if(50 <= $rated_films) {$result = true;} else {/*echo $grouped_rates[0]->rate;*/}

		return $result;
	}


	private function no_past($user_id)
	{
		// Вы оценили более 50 произведений, которые выпущены после 2000-го года
		$result = false;

		$new_books = DB::table('rates')
			->select('rate')
			->leftJoin('books', 'rates.element_id', '=', 'books.id')
			->where('rates.element_type', '=', 'Book')
			->where('rates.user_id', '=', $user_id)
			->where('books.year', '>', 2000)
			->limit(51)
			//->toSql()
			//->remember(60)
			->groupBy('rate')
			->count()
		;
		//;select * from rates left join books on rates.element_id = books.id where rates.user_id = 1 and books.`year` > 2000 limit 10
		//echo $new_books;

		$new_films = DB::table('rates')
			->select('rate')
			->leftJoin('films', 'rates.element_id', '=', 'films.id')
			->where('rates.element_type', '=', 'Film')
			->where('rates.user_id', '=', $user_id)
			->where('films.year', '>', 2000)
			->limit(51)
			//->toSql()
			//->remember(60)
			->groupBy('rate')
			->count()
		;
		//echo $new_films;

		$new_games = DB::table('rates')
			->select('rate')
			->leftJoin('games', 'rates.element_id', '=', 'games.id')
			->where('rates.element_type', '=', 'Game')
			->where('rates.user_id', '=', $user_id)
			->where('games.year', '>', 2000)
			->limit(51)
			//->toSql()
			//->remember(60)
			->groupBy('rate')
			->count()
		;
		//echo $new_games;

		if(50 <= $new_books || 50 <= $new_films || 50 <= $new_games) {$result = true;} else {/*echo $grouped_rates[0]->rate;*/}

		return $result;
	}


	private function vampire_plus($user_id)
	{
		// Вы оценили 50 книг Юрия Никитина и любой из фильмов трилогии «Сумерки»
		$result = false;

		$nikitin = 8995;

		$nikitin_books = DB::table('writers_books')
			->select('book_id')
			->where('person_id', '=', $nikitin)
			//->remember(60)
			->pluck('book_id')
		;

		$nikitin_books_rated = DB::table('rates')
			->select('element_id')
			->where('rates.user_id', '=', $user_id)
			->where('element_type', '=', 'Book')
			->whereIn('element_id', $nikitin_books)
			->limit(51)
			//->remember(60)
			->pluck('element_id')
		;

		$twilight = DB::table('films')
			->select(array('id', 'name', 'alt_name'))
			->where('name', 'like', 'Сумерки%')
			->limit(10)
			//->remember(60)
			->pluck('id')
		;

		$twilight_rated = DB::table('rates')
			->select('element_id')
			->where('rates.user_id', '=', $user_id)
			->where('element_type', '=', 'Film')
			->whereIn('element_id', $twilight)
			->limit(6)
			//->remember(60)
			->pluck('element_id')
		;

		// отключено до выяснения причин
		if(1 <= count($twilight_rated) && 50 <= count($nikitin_books_rated)) {$result = true;}
		else
		{
			//print_r($rated_genres);
			//echo count($nikitin_books_rated).' books, '.count($twilight_rated).' films';
		}

		return $result;
	}


	private function verbally_passionate($user_id)
	{
		// Вы оставили сто комментариев
		$result = false;

		$total_comments = DB::table('comments')
			->select('id')
			->where('user_id', '=', $user_id)
			->limit(101)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		if(100 <= $total_comments) {$result = true;} else {/*echo $total_comments;*/}

		return $result;
	}


	private function equal($user_id)
	{
		// Вы оценили больше 100 произведений, и больше половины — одинаково
		$result = false;

		$total_rates = DB::table('rates')
			->select('id')
			->where('user_id', '=', $user_id)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$grouped_rates = DB::table('rates')
			->select(DB::raw('count(*) as `count`, `rate`'))
			->where('user_id', '=', $user_id)
			->groupBy('rate')
			->orderBy('count', 'desc')
			//->remember(60)
			->get()
			//->toSql()
		;

		//print_r($grouped_rates);
		//echo $grouped_rates;

		$equal_rate = false;
		$half_rates = $total_rates/2;

		foreach($grouped_rates as $key => $value)
		{
			if($half_rates < $value->count) {$equal_rate = true;}
		}

		if(100 <= $total_rates && $equal_rate) {$result = true;} else {/*echo $grouped_rates[0]->rate;*/}

		return $result;
	}


	private function how_people_do($user_id)
	{
		// Вы оценили 100 произведений, и чаще всего ставили оценку 5
		$result = false;

		$total_rates = DB::table('rates')
			->select('id')
			->where('user_id', '=', $user_id)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$grouped_rates = DB::table('rates')
			->select(DB::raw('count(*) as `count`, `rate`'))
			->where('user_id', '=', $user_id)
			->groupBy('rate')
			->orderBy('count', 'desc')
			//->remember(60)
			->get()
			//->toSql()
		;

		//print_r($grouped_rates);
		//echo $grouped_rates;

		if(100 <= $total_rates && 5 == $grouped_rates[0]->rate) {$result = true;} else {/*echo $grouped_rates[0]->rate;*/}

		return $result;
	}


	private function invocation_of_incuriosity($user_id)
	{
		// Вы оценили 100 комиксов и 5 книг Нила Геймана
		$result = false;

		$comics = array(15);
		$gayman = 5842;

		//$rated_genres = array();
		$rated_genres = DB::table('elements_genres')
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $comics)
			->limit(100)
			->orderBy('elements_genres.genre_id', 'asc')
			//->remember(60)
			->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		$gayman_books = DB::table('writers_books')
			->select('book_id')
			->where('person_id', '=', $gayman)
			//->remember(60)
			->pluck('book_id')
		;

		$gayman_books_rated = DB::table('rates')
			->select('element_id')
			->where('element_type', '=', 'Book')
			->whereIn('element_id', $gayman_books)
			->limit(6)
			//->remember(60)
			->pluck('element_id')
		;

		if(100 <= count($rated_genres) && 5 <= count($gayman_books_rated)) {$result = true;}
		else
		{
			//print_r($rated_genres);
			//echo count($rated_genres).' comics, '.count($gayman_books_rated).' books';
		}

		return $result;
	}


	private function slash_and_eat($user_id)
	{
		// Вы оценили 100 триллеров, 50 фильмов ужасов, 100 комедий и 50 драм
		$result = false;

		$genres = array(
			'thriller' => 45,
			'horror' => 46,
			'comedy' => 28,
			'drama' => 25
		);

		//$rated_genres = array();
		$rated_genres = DB::table('elements_genres')
			->select(DB::raw('count(*) as `count`, `genre_id`'))
			//->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->groupBy('genre_id')
			->orderBy('elements_genres.genre_id', 'asc')
			//->remember(60)
			//->pluck('elements_genres.genre_id')
			->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		foreach($rated_genres as $key => $value)
		{
			if(array_search($value->genre_id, $genres))
			{
				if(45 == $value->genre_id)
				{
					if(100 <= $value->count)
					{
						$result = true;
					}
					else
					{
						//echo $value->genre_id.' — '.$value->count."\n";
						$result = false;
						break;
					}
				}

				if(46 == $value->genre_id)
				{
					if(50 <= $value->count)
					{
						$result = true;
					}
					else
					{
						//echo $value->genre_id.' — '.$value->count."\n";
						$result = false;
						break;
					}
				}

				if(28 == $value->genre_id)
				{
					if(100 <= $value->count)
					{
						$result = true;
					}
					else
					{
						//echo $value->genre_id.' — '.$value->count."\n";
						$result = false;
						break;
					}
				}

				if(25 == $value->genre_id)
				{
					if(50 <= $value->count)
					{
						$result = true;
					}
					else
					{
						//echo $value->genre_id.' — '.$value->count."\n";
						$result = false;
						break;
					}
				}

			}
		}

		//if(0 == count($all_genres)) {$result = true;} else {/*print_r($all_genres);*/}

		return $result;
	}


	private function artifactor($user_id)
	{
		// Вы оценили 200 произведений в жанре фэнтези
		$result = false;

		$fantasy = array(7, 47, 70);

		//$rated_genres = array();
		$rated_genres = DB::table('elements_genres')
			//->select(DB::raw('count(*) as `count`, `genre_id`'))
			->select('elements_genres.genre_id')
			//->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $fantasy)
			->orderBy('elements_genres.genre_id', 'asc')
			->limit(201)
			//->remember(60)
			->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(200 <= count($rated_genres)) {$result = true;} else {/*print_r($rated_genres);*/}

		return $result;
	}


	private function beyond_war($user_id)
	{
		// Вы оценили 200 произведений, но ни одно из них не было боевиком или военным фильмом
		$result = false;

		$war = array(3, 20, 22, 76, 79);

		$total_rates = DB::table('rates')
			->where('user_id', '=', $user_id)
			->limit(201)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		//$rated_genres = array();
		$rated_genres = DB::table('elements_genres')
			->select('elements_genres.genre_id')
			->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->whereIn('elements_genres.genre_id', $war)
			->orderBy('elements_genres.genre_id', 'asc')
			//->remember(60)
			->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		if(200 <= $total_rates && 0 == count($rated_genres)) {$result = true;} else {/*print_r($rated_genres);*/}

		return $result;
	}


	private function daddy($user_id)
	{
		// Вы оценили 100 книг, 100 фильмов и 100 игр
		$result = false;

		$result = false;

		$total_books = DB::table('rates')
			->where('element_type', '=', 'Book')
			->where('user_id', '=', $user_id)
			->limit(101)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_films = DB::table('rates')
			->where('element_type', '=', 'Film')
			->where('user_id', '=', $user_id)
			->limit(101)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_games = DB::table('rates')
			->where('element_type', '=', 'Game')
			->where('user_id', '=', $user_id)
			->limit(101)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		if(100 <= $total_books && 100 <= $total_films && 100 <= $total_games) {$result = true;}

		return $result;
	}


	private function formless($user_id)
	{
		// Вы оценили произведения всех имеющихся жанров
		$result = false;

		//$rated_genres = array();
		$rated_genres = DB::table('elements_genres')
			->select('elements_genres.genre_id')
			->distinct()
			->leftJoin('rates', 'elements_genres.element_id', '=', 'rates.element_id')
			->whereRaw('`rates`.`user_id` = '.$user_id.' AND `rates`.`element_type` = `elements_genres`.`element_type`')
			->orderBy('elements_genres.genre_id', 'asc')
			//->remember(60)
			->pluck('elements_genres.genre_id')
			//->get()
			//->toSql()
		;
		//print_r($rated_genres);
		//echo $rated_genres;

		//select distinct elements_genres.genre_id
		//from elements_genres
		//left join rates on elements_genres.element_id = rates.element_id
		//where rates.user_id = 1 and rates.element_type = elements_genres.element_type
		//order by elements_genres.genre_id;

		$all_genres = DB::table('genres')->whereNotIn('id', $rated_genres)->pluck('id'); //->remember(60)

		if(0 == count($all_genres)) {$result = true;} else {/*print_r($all_genres);*/}

		return $result;
	}


	private function thousand_lumens($user_id)
	{
		// Вы оценили тысячу книг, фильмов или игр
		$result = false;

		$total_books = DB::table('rates')
			->where('element_type', '=', 'Book')
			->where('user_id', '=', $user_id)
			->limit(1001)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_films = DB::table('rates')
			->where('element_type', '=', 'Film')
			->where('user_id', '=', $user_id)
			->limit(1001)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_games = DB::table('rates')
			->where('element_type', '=', 'Game')
			->where('user_id', '=', $user_id)
			->limit(1001)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		if(1000 <= $total_books || 1000 <= $total_films || 1000 <= $total_games) {$result = true;}

		return $result;
	}


	private function empeiria($user_id)
	{
		//Вы оценили 1000 книг, 1000 фильмов и 1000 игр
		$result = false;

		$total_books = DB::table('rates')
			->where('element_type', '=', 'Book')
			->where('user_id', '=', $user_id)
			->limit(1001)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_films = DB::table('rates')
			->where('element_type', '=', 'Film')
			->where('user_id', '=', $user_id)
			->limit(1001)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		$total_games = DB::table('rates')
			->where('element_type', '=', 'Game')
			->where('user_id', '=', $user_id)
			->limit(1001)
			//->remember(60)
			->groupBy('id')
			->count()
		;

		if(1000 <= $total_books && 1000 <= $total_films && 1000 <= $total_games) {$result = true;}

		return $result;
	}

}
