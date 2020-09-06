<?php

namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class BandsController extends Controller {

	protected string $section = 'bands';

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function list(Request $request) {

		$section = SectionsHelper::getSection($this->section);

		$sort = $request->get('sort', 'name');
		$order = $request->get('order', 'asc');
		$limit = 28;

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Имя',
		);

		$elements = $section->type::orderBy($sort, $order)->paginate($limit);

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'sort_options' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make('sections.'.$this->section.'.section', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'options' => $options,
		));

	}

	/**
	 * @param Request $request
	 * @param int $id
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
    public function item(Request $request, int $id) {

		$section = SectionsHelper::getSection($this->section);
		$element = $section->type::find($id);

		if(isset($element->id)) {

			$sort = $request->get('sort', 'name');
			$order = $request->get('order', 'asc');
			$limit = 28;

			$sort = TextHelper::checkSort($sort);
			$order = TextHelper::checkOrder($order);

			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'year' => 'Год'
			);

			$titles = array();
			$keywords = array();
			$albums = $members = array();
			if($element->albums->count()) {
				$keywords[] = 'альбомы';
				$titles['albums']['name'] = 'Альбомы';
				$titles['albums']['count'] = $element->albums->count();
				$albums = $element->albums()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->members->count()) {
				$keywords[] = 'участники';
				$titles['members']['name'] = 'Участники';
				$titles['members']['count'] = $element->members->members();
				$members = $element->members()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			//uasort($titles, array('TextHelper', 'compareReverseCount'));

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_options' => $sort_options,
				'sort' => $sort,
				'order' => $order,
				'limit' => $limit,
			);

			return View::make('sections.'.$this->section.'.item', array(
				'request' => $request,
				'section' => $section,
				'element' => $element,
				'titles' => $titles,
				'albums' => $albums,
				'members' => $members,
				'options' => $options
			));

		} else {

			return Redirect::to('/'.$this->section);

		}

    }
	
}