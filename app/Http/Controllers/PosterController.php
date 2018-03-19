<?php namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Redirect;

use App\Models\Helpers;
use App\Models\Poster;

class PosterController extends Controller {

	/**
	 * @return mixed
	 */
    public function search() {

		$query = addcslashes(Input::get('query', ''), '%');

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