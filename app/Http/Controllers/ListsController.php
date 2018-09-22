<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 02.09.2018
 * Time: 12:17
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElementList;
use App\Models\Event;
use App\Models\Helpers\RolesHelper;
use App\Models\Helpers\TextHelper;
use App\Models\Lists;
use Auth;
use View;

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

		$filter = $request->get('filter', array());

		$lists = new Lists();

		$lists_list = array();

		if(0 != Auth::user()->id) {

			$lists_list = $lists
				->where('user_id', '=', Auth::user()->id)
				->paginate($this->limit)
			;

		}

		return View::make('lists.get_lists', array('lists_list' => $lists_list));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function addList(Request $request) {

		$fields = $request->get('fields', array());

		$lists = new Lists();

		$list = array();

		if(0 != Auth::user()->id) {

			$lists->name = $fields['name'];
			$lists->description = TextHelper::wordsLimit($fields['description']);
			if(RolesHelper::isAdmin($request) && isset($fields['public_list']) && ('public_list' == $fields['public_list'])) {
				$lists->section = $this->public_list_type;
			} else {
				$lists->section = $this->private_list_type;
			}
			$lists->user_id = Auth::user()->id;
			$lists->save();

			$event = new Event();
			$event->event_type = 'Lists';
			$event->element_type = $lists->section;
			$event->element_id =  $lists->id;
			$event->user_id = Auth::user()->id;
			$event->name = 'Создан список';
			$event->text = '«'.$lists->name.'»';
			$event->save();

			$list = $lists->get();

		}

		return View::make('lists.add_list', array('list' => $list));

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

		$lists = new Lists();

		$list = array();

		if(0 != Auth::user()->id) {

			$lists->find($list_id);

			if(Auth::user()->id == $lists->user_id) {

				$lists->delete();

			}

		}

		return View::make('lists.remove_list', array('list' => $list));

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

		$element_data = $request->get('element_data', array());
		$list_id = $request->get('list_id', 0);

		$lists = new Lists();
		$element_list = new ElementList();

		$element = array();

		if(0 != Auth::user()->id) {

			$lists->find($list_id);

			if(Auth::user()->id == $lists->user_id) {

				$element_list->element_id = $element_data['element_id'];
				$element_list->element_type = $element_data['element_type'];
				$element_list->list_id = $list_id;
				$element_list->user_id = Auth::user()->id;

				$element = $element_list->get();

			}

		}

		return View::make('lists.add_to_list', array('element' => $element));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function removeFromList(Request $request) {

		$id = $request->get('id', 0);
		$filter = $request->get('filter', array());

		$element_list = new ElementList();

		$element = array();

		if(0 != Auth::user()->id) {

			$element_list->find($id);

			if(Auth::user()->id == $element_list->user_id) {

				$element_list->delete();

			}

		}

		return View::make('lists.remove_list', array('element' => $element));

	}

}