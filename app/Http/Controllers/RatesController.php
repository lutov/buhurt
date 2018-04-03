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

	public function goHome() {

		return Redirect::to('/');

	}

	/**
	 * @param string $section
	 * @param string $id
	 */
    public function rate(string $section = '', string $id = '') {

		$rate_val = Input::get('rate_val', 0);

		//$section = Section::where('alt_name', '=', $section)->first()->type;

		//die($section);

		if('books' == $section) {
			$element = Book::find($id);
			$type = 'Book';
		} elseif('films' == $section) {
			$element = Film::find($id);
			$type = 'Film';
		} elseif('games' == $section) {
			$element = Game::find($id);
			$type = 'Game';
		} elseif('albums' == $section) {
			$element = Album::find($id);
			$type = 'Album';
		} else {
			$element = array();
			$type = '';
		}

		//die(print_r($element, true));

		if(!empty($element)) {

			// Выбор пользователя
			$user = Auth::user();

			$exists = $element->rates()->where('user_id', '=', $user->id)->first();
			//die(print_r($exists, true));
			if(isset($exists->id)) {
				$rate = Rate::find($exists->id);
				$rate->rate = $rate_val;
				$rate->save();
			} else {
				// Создание новой оценки
				$rate = new Rate();
				$rate->rate = $rate_val;
				$rate->element_id = $id;
				$rate->element_type = $type;
				// Сохранение оценки
				$user->rates()->save($rate);
			}

			// success
			echo '{"msg_type": "rate", "message": "Оценка&nbsp;сохранена", "status":"OK"}';
		}
    }


	public function unrate($section, $id) {

		if('books' == $section) {
			$element = Book::find($id);
			$type = 'Book';
		} elseif('films' == $section) {
			$element = Film::find($id);
			$type = 'Film';
		} elseif('games' == $section) {
			$element = Game::find($id);
			$type = 'Game';
		} elseif('albums' == $section) {
			$element = Album::find($id);
			$type = 'Album';
		} else {
			$element = array();
		}

		if(!empty($element)) {

			// Выбор пользователя
			$user = Auth::user();

			$exists = $element->rates()->where('user_id', '=', $user->id)->first();
			//die(print_r($exists, true));
			if(isset($exists->id)) {
				$rate = Rate::find($exists->id);
				$rate->delete();
				//$rate->rate = $rate_val;
				//$rate->save();
			} else {
				//
			}

		}

		return Redirect::back()->with('message', 'Оценка удалена');
	}
	
}