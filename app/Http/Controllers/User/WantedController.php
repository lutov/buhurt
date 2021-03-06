<?php namespace App\Http\Controllers\User;

use App\Helpers\SectionsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\Wanted;

class WantedController extends Controller {

	private $section = 'wanted';

	/**
	 * @param $section
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function setWanted($section, $id) {

    	$result = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$section = SectionsHelper::getSection($section);
			$type = $section->type;

			$exists = Wanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first()
			;

			if(!isset($exists->id)) {

				$wanted = new Wanted();
				$wanted->user_id = $user_id;
				$wanted->element_type = $type;
				$wanted->element_id = $id;
				$wanted->save();

				$result = array("message" => "Произведение добавлено в список желаемого");

			} else {

				$wanted = $exists;

				$result = array("message" => "Произведение уже в списке желаемого");

			}

		}

		return response()->json($result);

    }

	/**
	 * @param $section
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
    public function unsetWanted($section, $id) {

		$result = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$section = SectionsHelper::getSection($section);
			$type = $section->type;

			$exists = Wanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first()
			;

			if(isset($exists->id)) {

				$exists->delete();
				$result = array("message" => "Произведение удалено из списка желаемого");

			} else {

				$result = array("message" => "Произведение отсутствует в списке желаемого");

			}
		}

		return response()->json($result);

    }

}