<?php namespace App\Http\Controllers;

use App\Models\Helpers\DebugHelper;
use App\Models\Helpers\RolesHelper;
use Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use Input;
use Redirect;
use View;

class EventsController extends Controller {

	private $section = 'events';
	private $section_name = 'События';
	private $object_name = 'App\Models\Event';
	private $limit = 28;

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function getList(Request $request) {

		if(!Auth::check()) {return Redirect::to('/');}

		$sort = Input::get('sort', $this->section . '.created_at');
		$sort_direction = Input::get('sort_direction', 'desc');

		if(RolesHelper::isAdmin($request)) {

			$elements = $this->object_name::orderBy($sort, $sort_direction)
				->paginate($this->limit)
			;

		} else {

			$elements = $this->object_name::orderBy($sort, $sort_direction)
				->where('user_id', '=', Auth::user()->id)
				->paginate($this->limit)
			;

		}

		return View::make('events.list', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $this->section,
			'section_name' => $this->section_name,
		));

	}
	
}