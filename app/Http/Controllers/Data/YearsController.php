<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class YearsController extends Controller {

	protected string $section = 'years';

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
		$order = $request->get('order', 'desc');
		$limit = 28;

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$sort_options = array(
			'year' => 'Год',
			'count' => 'Число произведений'
		);

		$tabs = array();
		foreach($this->sections as $genre_section) {
            $entity = $genre_section['section']; // TODO check naming logic
            $name = $genre_section['name'];
            $elements = $this->getSectionYears($entity, $sort, $order);
            $count = $elements->count();
            if(0 != $count) {
                $tabs[$entity]['slug'] = $entity;
                $tabs[$entity]['name'] = $name;
                $tabs[$entity]['count'] = $count;
                $tabs[$entity]['section'] = SectionsHelper::getSection('years');
                $tabs[$entity]['elements'] = $elements;
            }
		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => false,
			'sort_options' => $sort_options,
			'sort' => $sort,
			'order' => $order,
			'count' => true,
			'columns' => array(
				'count' => 10,
				'width' => '5em'
			),
		);

		return View::make('sections.'.$this->section.'.section', array(
			'request' => $request,
			'tabs' => $tabs,
			'section' => $section,
			'options' => $options,
		));

	}

	/**
	 * @param Request $request
	 * @param int $year
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function item(Request $request, int $year) {

		$section = SectionsHelper::getSection($this->section);
		$element = new $section->type;
		$element->name = $year;

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
		$books = $films = $games = $albums = array();
		foreach($this->sections as $genre_section) {
            $type = $genre_section['type'];
            $entity = $genre_section['section']; // TODO check naming logic
            $name = $genre_section['name'];
            $elements = $this->getYearElements($type, $year, $sort, $order, $limit);
            $count = $elements->count();
            if(0 != $count) {
                $tabs[$entity]['slug'] = $entity;
                $tabs[$entity]['name'] = $name;
                $tabs[$entity]['count'] = $count;
                $tabs[$entity]['section'] = SectionsHelper::getSection($entity);
                $tabs[$entity]['elements'] = $elements;
            }
		}

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
			'books' => $books,
			'films' => $films,
			'games' => $games,
			'albums' => $albums,
			'options' => $options
		));

	}

	/**
	 * @param string $section
	 * @param string $sort
	 * @param string $order
	 * @param bool $cache
	 * @return Collection|mixed
	 */
    private function getSectionYears(string $section, string $sort, string $order, bool $cache = true) {
		if($cache) {
			$minutes = 60;
			$var_name = 'years_'.$section.'_'.$sort.'_'.$order; //Cache::forget($var_name);
			$elements = Cache::remember($var_name, $minutes, function () use ($section, $sort, $order) {
				return DB::table($section)
					->selectRaw('`year` as `id`, `year` as `name`, count(id) as `count`')
					->groupBy('year')
					->orderBy($sort, $order)
					->get()
				;
			});
		} else {
			$elements = DB::table($section)
				->selectRaw('`year` as `id`, `year` as `name`, count(id) as `count`')
				->groupBy('year')
				->orderBy($sort, $order)
				->get()
			;
		}
		return $elements;
	}

	/**
	 * @param string $type
	 * @param int $year
	 * @param string $sort
	 * @param string $order
	 * @param int $limit
	 * @return mixed
	 */
	private function getYearElements(string $type, int $year, string $sort, string $order, int $limit) {
		return $type::where('year', $year)
			->orderBy($sort, $order)
			->paginate($limit)
		;
	}

	/**
	 * @param string $type
	 * @param $element
	 * @return mixed
	 */
	private function countYearElements(string $type, $element) {
		$minutes = 60;
		$var_name = 'year_'.$element->name.'_'.$type.'_count';
		return Cache::remember($var_name, $minutes, function () use ($type, $element) {
			return $type::where('year', $element->name)->count();
		});
	}
	
}