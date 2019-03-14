<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Wanted;
use App\Models\Data\Section;

class WantedController extends Controller {

	/**
	 * @param $section
	 * @param $id
	 */
    public function like($section, $id) {

		if(Auth::check()) {

			$user_id = Auth::user()->id;

			$type = Section::where('alt_name', '=', $section)->first()->type;

			$exists = Wanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first()
			;
			//die(print_r($exists, true));
			if(!isset($exists->id)) {
				$wanted = new Wanted();
				$wanted->user_id = $user_id;
				$wanted->element_type = $type;
				$wanted->element_id = $id;
			}
			else {
				$wanted = $exists;
			}

			$wanted->wanted = 1;
			$wanted->save();
			echo '{"message":"Произведение&nbsp;добавлено&nbsp;в&nbsp;список&nbsp;желаемого"}';
		}
    }

	/**
	 * @param $section
	 * @param $id
	 */
    public function unlike($section, $id) {

		if(Auth::check()) {
			$user_id = Auth::user()->id;

			$type = Section::where('alt_name', '=', $section)->first()->type;

			$exists = Wanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first()
			;
			//die(print_r($exists, true));
			if(isset($exists->id)) {
				$exists->wanted = 0;
				$exists->save();
				echo '{"message":"Произведение&nbsp;удалено&nbsp;из&nbsp;списка&nbsp;желаемого"}';
			}
			else
			{
				echo '{"message":"Не&nbsp;удалось&nbsp;удалить&nbsp;произведение&nbsp;из&nbsp;списка"}';
			}
		}
    }

	/**
	 * @param $section
	 * @param $id
	 */
    public function dislike($section, $id) {

		if(Auth::check()) {

			$user_id = Auth::user()->id;

			$type = Section::where('alt_name', '=', $section)->first()->type;

			$exists = Wanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first();
			if(!isset($exists->id)) {
				$wanted = new Wanted();
				$wanted->user_id = $user_id;
				$wanted->element_type = $type;
				$wanted->element_id = $id;
			}
			else {
				$wanted = $exists;
			}

			$wanted->not_wanted = 1;
			$wanted->save();
			echo '{"message":"Произведение&nbsp;добавлено&nbsp;в&nbsp;список&nbsp;нежелаемого"}';
		}
    }

	/**
	 * @param $section
	 * @param $id
	 */
	public function undislike($section, $id) {

		if(Auth::check()) {

			$user_id = Auth::user()->id;

			$type = Section::where('alt_name', '=', $section)->first()->type;

			$exists = Wanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first()
			;
			//die(print_r($exists, true));
			if(isset($exists->id)) {
				$exists->not_wanted = 0;
				$exists->save();
				echo '{"message":"Произведение&nbsp;удалено&nbsp;из&nbsp;списка&nbsp;нежелаемого"}';

			} else {

				echo '{"message":"Не&nbsp;удалось&nbsp;удалить&nbsp;произведение&nbsp;из&nbsp;списка"}';

			}

		}

	}
	
}