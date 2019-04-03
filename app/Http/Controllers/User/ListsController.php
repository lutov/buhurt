<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 02.09.2018
 * Time: 12:17
 */

namespace App\Http\Controllers\User;

use App\Helpers\SectionsHelper;
use App\Http\Controllers\Controller;
use App\Models\Data\Section;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Search\ElementList;
use App\Models\User\Event;
use App\Helpers\RolesHelper;
use App\Helpers\TextHelper;
use App\Models\User\Lists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ListsController extends Controller {

	/**
	 * @var int
	 */
	private $limit = 28;

	/**
	 * @var string
	 */
	private $public_list_type = 'PublicList';
	/**
	 * @var string
	 */
	private $private_list_type = 'PrivateList';

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getLists(Request $request) {

		$lists = new Lists();

		$result = array();

		if(Auth::check()) {

			$user = Auth::user();

			$result = $lists
				->where('user_id', '=', $user->id)
				->paginate($this->limit)
			;

		}

		return response()->json($result);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function addList(Request $request) {

		$name = $request->get('name');

		$result = array();

		if(Auth::check()) {

			$isPublic = false;

			$user = Auth::user();

			$list = new Lists();
			$list->name = $name;
			$list->description = '';//TextHelper::wordsLimit($fields['description']);
			if(RolesHelper::isAdmin($request) && $isPublic) {
				$list->section = $this->public_list_type;
			} else {
				$list->section = $this->private_list_type;
			}
			$list->user_id = $user->id;
			$list->save();

			$event = new Event();
			$event->event_type = 'Lists';
			$event->element_type = $list->section;
			$event->element_id =  $list->id;
			$event->user_id = Auth::user()->id;
			$event->name = 'Создан список';
			$event->text = '«'.$list->name.'»';
			$event->save();

			$result = $list;

		}

		return response()->json($result);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function editList(Request $request) {

		$list_id = $request->get('list_id', 0);
		$fields = $request->get('fields', array());

		$lists = new Lists();

		$list = array();

		if(0 != Auth::user()->id) {

			$lists->find($list_id);

			if(Auth::user()->id == $lists->user_id) {

				$lists->name = $fields['name'];
				$lists->description = TextHelper::wordsLimit($fields['description']);
				$lists->save();

				$list = $lists->get();

			}

		}

		return View::make('lists.edit_list', array('list' => $list));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function removeList(Request $request) {

		$list_id = $request->get('list_id', 0);

		$result = array();

		if(Auth::check()) {

			$user = Auth::user();

			$list = Lists::find($list_id);

			if($user->id == $list->user_id) {

				$list->delete();

				$result = $list;

			}

		}

		return response()->json($result);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getList(Request $request) {

		$list_id = $request->get('list_id', 0);

		$lists = new Lists();

		$list = array();

		if(0 != Auth::user()->id) {

			$lists->find($list_id);

			if(Auth::user()->id == $lists->user_id) {

				$list = $lists->get();

			}

		}

		return View::make('lists.get_list', array('list' => $list));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function addToList(Request $request) {

		$id = $request->get('id', array());
		$section = SectionsHelper::getSection($request->get('section'));
		$list_id = $request->get('list_id', 0);

		$result = array();

		if(Auth::check()) {

			$user = Auth::user();

			$list = Lists::find($list_id);

			if($user->id == $list->user_id) {

				$element = $this->getElement($id, $section, $list_id);

				if(empty($element)) {

					$element = new ElementList();

					$element->element_id = $id;
					$element->element_type = $section->type;
					$element->list_id = $list_id;
					$element->user_id = $user->id;

					$element->save();

				}

				$result = $element;

			}

		}

		return response()->json($result);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function removeFromList(Request $request) {

		$id = $request->get('id', array());
		$section = SectionsHelper::getSection($request->get('section'));
		$list_id = $request->get('list_id', 0);

		$result = array();

		if(Auth::check()) {

			$user = Auth::user();

			$list = Lists::find($list_id);

			if($user->id == $list->user_id) {

				$element = $this->getElement($id, $section, $list_id);

				if(!empty($element)) {$element->delete();}

			}

		}

		return response()->json($result);

	}

	/**
	 * @param int $id
	 * @param Section $section
	 * @param int $list_id
	 * @return mixed
	 */
	public function getElement(int $id = 0, Section $section, int $list_id = 0) {

		$element = ElementList::where('element_type', '=', $section->type)
			->where('element_id', '=', $id)
			->where('list_id', '=', $list_id)
			->first()
		;

		return $element;

	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function findElement(Request $request) {

		$id = $request->get('id', array());
		$section = SectionsHelper::getSection($request->get('section'));
		$list_id = $request->get('list_id', 0);

		$element = $this->getElement($id, $section, $list_id);

		return response()->json($element);

	}

}