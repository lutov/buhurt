<?php

namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class PlatformsController extends Controller {

	protected string $section = 'platforms';

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
				'alt_name' => 'Оригинальное название',
				'year' => 'Год'
			);

			$tabs = array();
			$keywords = array();
			if($element->games->count()) {
				$keywords[] = 'игры';
				$tabs['games']['slug'] = 'games';
				$tabs['games']['name'] = 'Игры';
				$tabs['games']['count'] = $element->games->count();
                $tabs['games']['section'] = SectionsHelper::getSection('games');
                $tabs['games']['elements'] = $element->games()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			//uasort($tabs, array('TextHelper', 'compareReverseCount'));

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_options' => $sort_options,
				'sort' => $sort,
				'order' => $order,
			);

			return View::make('sections.'.$this->section.'.item', array(
				'request' => $request,
				'tabs' => $tabs,
				'element' => $element,
				'section' => $section,
				'options' => $options
			));

		} else {

			return Redirect::to('/'.$this->section);

		}

	}
	
}