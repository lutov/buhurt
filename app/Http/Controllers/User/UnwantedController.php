<?php namespace App\Http\Controllers\User;

use App\Helpers\SectionsHelper;
use App\Http\Controllers\Controller;
use App\Models\User\Unwanted;
use Illuminate\Support\Facades\Auth;

class UnwantedController extends Controller {

	private $section = 'unwanted';

	/**
	 * @param $section
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function setUnwanted($section, $id) {

		$result = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$section = SectionsHelper::getSection($section);
			$type = $section->type;

			$exists = Unwanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first()
			;

			if(!isset($exists->id)) {

				$unwanted = new Unwanted();
				$unwanted->user_id = $user_id;
				$unwanted->element_type = $type;
				$unwanted->element_id = $id;
				$unwanted->save();

				$result = array("message" => "Произведение добавлено в список нежелаемого");

			} else {

				$unwanted = $exists;

				$result = array("message" => "Произведение уже находится в списке нежелаемого");

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
	public function unsetUnwanted($section, $id) {

		$result = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;
			$section = SectionsHelper::getSection($section);
			$type = $section->type;

			$exists = Unwanted::where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->where('user_id', '=', $user_id)
				->first()
			;

			if(isset($exists->id)) {

				$exists->delete();

				$result = array("message" => "Произведение удалено из списка нежелаемого");

			} else {

				$result = array("message" => "Произведение отсутствует в списке нежелаемого");

			}

		}

		return response()->json($result);

	}
	
}