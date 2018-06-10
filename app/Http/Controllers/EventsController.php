<?php namespace App\Http\Controllers;

use App\Models\Helpers\DebugHelper;
use Illuminate\Http\Request;
use App\Models\Event;
use Input;
use View;

class EventsController extends Controller {

	private $section = 'events';
	private $section_name = 'События';
	private $object_name = 'App\Models\Event';
	private $limit = 28;

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getList(Request $request) {

		$sort = Input::get('sort', $this->section . '.created_at');
		$sort_direction = Input::get('sort_direction', 'desc');

		$elements = $this->object_name::orderBy($sort, $sort_direction)
			->paginate($this->limit)
		;

		return View::make('events.list', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $this->section,
			'section_name' => $this->section_name,
		));

	}
	
}