<?php

namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Data\Person;

class PersonsController extends Controller {

	protected string $section = 'persons';

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
	 * @return mixed
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
			$books = $screenplays = $directions = $productions = $roles = array();
			if($element->books->count()) {
				$keywords[] = 'писатель';
				$titles['writer']['name'] = 'Писатель';
				$titles['writer']['count'] = $element->books->count();
				$books = $element->books()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->screenplays->count()) {
				$keywords[] = 'сценарист';
				$titles['screenwriter']['name'] = 'Сценарист';
				$titles['screenwriter']['count'] = $element->screenplays->count();
				$screenplays = $element->screenplays()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->directions->count()) {
				$keywords[] = 'режиссёр';
				$titles['director']['name'] = 'Режиссёр';
				$titles['director']['count'] = $element->directions->count();
				$directions = $element->directions()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->productions->count()) {
				$keywords[] = 'продюссер';
				$titles['producer']['name'] = 'Продюссер';
				$titles['producer']['count'] = $element->productions->count();
				$productions = $element->productions()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->roles->count()) {
				$keywords[] = 'актёр';
				$titles['actor']['name'] = 'Актёр';
				$titles['actor']['count'] = $element->roles->count();
				$roles = $element->roles()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			uasort($titles, array('TextHelper', 'compareReverseCount'));

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
				'options' => $options,
				'titles' => $titles,
				'keywords' => $keywords,
				'books' => $books,
				'screenplays' => $screenplays,
				'directions' => $directions,
				'productions' => $productions,
				'roles' => $roles,
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

		DB::table('writers_books')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('directors_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('screenwriters_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('producers_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('writers_genres')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));
		DB::table('actors_films')->where('person_id', '=', $id)->update(array('person_id' => $recipient_id));

		DB::table('persons')->where('id', '=', $id)->delete();

		return Redirect::to('/'.$this->section.'/'.$recipient_id);

	}
	
}