<?php

namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class CompaniesController extends Controller {

	protected string $section = 'companies';

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

			$tabs = array();
			$keywords = array();
			if($element->books_published->count()) {
				$keywords[] = 'книги';
                $tabs['book_publisher']['slug'] = 'book_publisher';
				$tabs['book_publisher']['name'] = 'Книги';
				$tabs['book_publisher']['count'] = $element->books_published->count();
                $tabs['book_publisher']['section'] = SectionsHelper::getSection('books');
                $tabs['book_publisher']['elements'] = $element->books_published()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->games_developed->count()) {
				$keywords[] = 'разработанные игры';
                $tabs['game_developer']['slug'] = 'game_developer';
				$tabs['game_developer']['name'] = 'Разработанные игры';
				$tabs['game_developer']['count'] = $element->games_developed->count();
                $tabs['game_developer']['section'] = SectionsHelper::getSection('games');
                $tabs['game_developer']['elements'] = $element->games_developed()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->games_published->count()) {
				$keywords[] = 'изданные игры';
                $tabs['game_publisher']['slug'] = 'game_publisher';
				$tabs['game_publisher']['name'] = 'Изданные игры';
				$tabs['game_publisher']['count'] = $element->games_published->count();
                $tabs['game_publisher']['section'] = SectionsHelper::getSection('games');
                $tabs['game_publisher']['elements'] = $element->games_published()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			uasort($tabs, array('TextHelper', 'compareReverseCount'));

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
				'section' => $section,
				'tabs' => $tabs,
				'element' => $element,
				'options' => $options,
			));

		} else {

			return Redirect::to('/'.$this->section);

		}

    }

	/**
	 * @param Request $request
	 * @param int $id
	 * @return mixed
	 */
	public function transfer(Request $request, int $id = 0) {

		$recipient_id = $request->get('recipient_id');

		DB::table('developers_games')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));
		DB::table('publishers_games')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));
		DB::table('publishers_books')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));

		DB::table('companies')->where('id', '=', $id)->delete();//->update(array('name' => ''));

		return Redirect::to('/'.$this->section.'/'.$recipient_id);

	}
}