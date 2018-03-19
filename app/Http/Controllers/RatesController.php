<?php namespace App\Http\Controllers;

use Auth;
use Input;
use Redirect;
use App\Models\Book;
use App\Models\Film;
use App\Models\Game;
use App\Models\Rate;
use App\Models\Album;

class RatesController extends Controller {

	private $prefix = '';

	public function goHome()
	{
		return Redirect::to('/');
	}

    public function rate()
    {
		$vote_id = Input::get('vote_id');
		$vote_info = explode('/', $vote_id);
		//die($vote_id);
		$section = $vote_info[0];
		$id = $vote_info[1];

		//$section = Section::where('alt_name', '=', $section)->first()->type;

		//die($section);

		if('books' == $section)
		{
			$element = Book::find($id);
			$type = 'Book';
		}
		elseif('films' == $section)
		{
			$element = Film::find($id);
			$type = 'Film';
		}
		elseif('games' == $section)
		{
			$element = Game::find($id);
			$type = 'Game';
		}
		elseif('albums' == $section)
		{
			$element = Album::find($id);
			$type = 'Album';
		}
		else
		{
			$element = array();
		}

		//die(print_r($element, true));

		if(!empty($element))
		{
			// Выбор пользователя
			$user = Auth::user();

			$exists = $element->rates()->where('user_id', '=', $user->id)->first();
			//die(print_r($exists, true));
			if(isset($exists->id))
			{
				$rate = Rate::find($exists->id);
				$rate->rate = Input::get('val', 0);
				$rate->save();
			}
			else
			{
				// Создание новой оценки
				$rate = new Rate();
				$rate->rate = Input::get('val', 0);
				$rate->element_id = $id;
				$rate->element_type = $type;
				// Сохранение оценки
				$user->rates()->save($rate);
			}

			// success
			echo '{"msg_type": "rate", "message": "Оценка&nbsp;сохранена", "status":"OK"}';
		}
    }


	public function unrate($section, $id)
	{
		if('books' == $section)
		{
			$element = Book::find($id);
			$type = 'Book';
		}
		elseif('films' == $section)
		{
			$element = Film::find($id);
			$type = 'Film';
		}
		elseif('games' == $section)
		{
			$element = Game::find($id);
			$type = 'Game';
		}
		elseif('albums' == $section)
		{
			$element = Album::find($id);
			$type = 'Album';
		}
		else
		{
			$element = array();
		}

		if(!empty($element))
		{
			// Выбор пользователя
			$user = Auth::user();

			$exists = $element->rates()->where('user_id', '=', $user->id)->first();
			//die(print_r($exists, true));
			if(isset($exists->id))
			{
				$rate = Rate::find($exists->id);
				$rate->delete();
				//$rate->rate = Input::get('val', 0);
				//$rate->save();
			}
			else
			{
				//
			}

			// success
			//echo '{"status": "OK","message": "Оценка сохранена"}';
		}

		return Redirect::back()->with('message', 'Оценка удалена');
	}
	
}