<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Helpers\RolesHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class EventsController extends Controller {

	private $section = 'events';
	private $section_name = 'События';
	private $object_name = 'App\Models\User\Event';
	private $limit = 28;

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function getList(Request $request) {

		if(!Auth::check()) {return Redirect::to('/');}

		$sort = $request->get('sort', $this->section . '.created_at');
		$sort_direction = $request->get('sort_direction', 'desc');

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