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

			$tabs = array();
			$keywords = array();
			if($element->books->count()) {
				$keywords[] = 'писатель';
				$tabs['writer']['slug'] = 'writer';
				$tabs['writer']['name'] = 'Писатель';
				$tabs['writer']['count'] = $element->books->count();
				$tabs['writer']['section'] = SectionsHelper::getSection('books');
                $tabs['writer']['elements'] = $element->books()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->screenplays->count()) {
				$keywords[] = 'сценарист';
				$tabs['screenwriter']['slug'] = 'screenwriter';
				$tabs['screenwriter']['name'] = 'Сценарист';
				$tabs['screenwriter']['count'] = $element->screenplays->count();
                $tabs['screenwriter']['section'] = SectionsHelper::getSection('films');
                $tabs['screenwriter']['elements'] = $element->screenplays()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->directions->count()) {
				$keywords[] = 'режиссёр';
				$tabs['director']['slug'] = 'director';
				$tabs['director']['name'] = 'Режиссёр';
				$tabs['director']['count'] = $element->directions->count();
                $tabs['director']['section'] = SectionsHelper::getSection('films');
                $tabs['director']['elements'] = $element->directions()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->productions->count()) {
				$keywords[] = 'продюссер';
				$tabs['producer']['slug'] = 'producer';
				$tabs['producer']['name'] = 'Продюссер';
				$tabs['producer']['count'] = $element->productions->count();
                $tabs['producer']['section'] = SectionsHelper::getSection('films');
                $tabs['producer']['elements'] = $element->productions()
					->orderBy($sort, $order)
					->paginate($limit)
				;
			}
			if($element->roles->count()) {
				$keywords[] = 'актёр';
				$tabs['actor']['slug'] = 'actor';
				$tabs['actor']['name'] = 'Актёр';
				$tabs['actor']['count'] = $element->roles->count();
                $tabs['actor']['section'] = SectionsHelper::getSection('films');
                $tabs['actor']['elements'] = $element->roles()
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
				'limit' => $limit,
			);

			return View::make('sections.'.$this->section.'.item', array(
				'request' => $request,
				'section' => $section,
				'element' => $element,
				'options' => $options,
				'tabs' => $tabs,
				'keywords' => $keywords,
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
