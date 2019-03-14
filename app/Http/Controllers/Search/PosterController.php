<?php namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use App\Models\Search\Poster;

class PosterController extends Controller {

	/**
	 * @param Request $request
	 * @return mixed
	 */
    public function search(Request $request) {

		$query = addcslashes($request->get('query', ''), '%');

		$elements = array();

		if(!empty($query)) {

			$elements = Poster::where('name', 'LIKE', '%' . $query . '%')
				->get()
				->toArray()
			;
		}

		return View::make('api.poster', array(
			'elements' => $elements,
		));
    }
	
}