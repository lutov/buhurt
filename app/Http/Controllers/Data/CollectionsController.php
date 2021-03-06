<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class CollectionsController extends Controller {

	protected string $section = 'collections';

	protected array $sections = array(
		array('section' => 'books', 'type' => 'Book', 'name' => 'Книги'),
		array('section' => 'films', 'type' => 'Film', 'name' => 'Фильмы'),
		array('section' => 'games', 'type' => 'Game', 'name' => 'Игры'),
		array('section' => 'albums', 'type' => 'Album', 'name' => 'Альбомы'),
	);

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
			foreach($this->sections as $genre_section) {
				$entity = $genre_section['section']; // TODO check naming logic
				$name = $genre_section['name'];
				$count = $this->countCollectionElements($entity, $element);
				if(0 != $count) {
                    $tabs[$entity]['slug'] = $entity;
                    $tabs[$entity]['name'] = $name;
                    $tabs[$entity]['count'] = $count;
                    $tabs[$entity]['section'] = SectionsHelper::getSection($entity);;
                    $tabs[$entity]['elements'] = $this->getCollectionElements($entity, $element, $sort, $order, $limit);
                }
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
				'tabs' => $tabs,
				'element' => $element,
				'section' => $section,
				'options' => $options
			));

		} else {

			return Redirect::to('/'.$this->section);

		}

	}

	/**
	 * @param string $section
	 * @param $element
	 * @param string $sort
	 * @param string $order
	 * @param int $limit
	 * @return mixed
	 */
	private function getCollectionElements(string $section, $element, string $sort, string $order, int $limit) {
		return $element->{$section}()
			->orderBy($sort, $order)
			->paginate($limit)
			;
	}

	/**
	 * @param string $section
	 * @param $element
	 * @return mixed
	 */
	private function countCollectionElements(string $section, $element) {
		$minutes = 60;
		$var_name = 'collection_'.$element->id.'_'.$section.'_count';
		return Cache::remember($var_name, $minutes, function () use ($section, $element) {
			return $element->{$section}()->count();
		});
	}
	
}