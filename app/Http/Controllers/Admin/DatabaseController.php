<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use EMTypograph;
use Exception;
use Illuminate\Http\RedirectResponse;
use ResizeCrop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\User\Event;
use App\Helpers\RolesHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Helpers\ElementsHelper;
use App\Models\User\Uploader;

class DatabaseController extends Controller {

	/**
	 * @param Request $request
	 * @param string $section
	 * @return \Illuminate\Contracts\View\View
	 */
	public function add(Request $request, string $section = '') {

		return View::make('database.add', array(
			'request' => $request,
			'section' => $section,
		));

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param int $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function edit(Request $request, string $section, int $id) {

		$element = SectionsHelper::getSectionType($section)::find($id);

		return View::make('database.edit.'.$section, array(
			'request' => $request,
			'section' => $section,
			'element' => $element,
		));
	}

	/**
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function save(Request $request) {

		$validator = Validator::make(
			$_POST,
			array(
				'name' => array('required', 'min:1'),
				'cover' => array('image', 'max:100')
			)
		);

		if ($validator->fails()) {

			return Redirect::back()->withInput()->withErrors($validator);

		} else {

			return $this->saveElement($request);

		}

	}

	/**
	 * @param Request $request
	 * @return RedirectResponse
	 */
	private function saveElement(Request $request) {

		$action = $request->get('action', '');

		$id = $request->get('element_id');
		$section = $request->get('section');
		$type = SectionsHelper::getSectionType($section);

		$name = $request->get('name');
		$alt_name = $request->get('alt_name');
		$description = $request->get('description', '');
		$year = $request->get('year');

		$genres = explode('; ', $request->get('genres'));
		$collections = explode('; ', $request->get('collections'));

		$writers = explode('; ', $request->get('writers'));
		$books_publishers = explode('; ', $request->get('books_publishers'));

		$directors = explode('; ', $request->get('directors'));
		$screenwriters = explode('; ', $request->get('screenwriters'));
		$producers = explode('; ', $request->get('producers'));
		$actors = explode('; ', $request->get('actors'));
		$countries = explode('; ', $request->get('countries'));
		$length = $request->get('length');

		$platforms = explode('; ', $request->get('platforms'));
		$developers = explode('; ', $request->get('developers'));
		$games_publishers = explode('; ', $request->get('games_publishers'));

		$tracks = $request->get('tracks', array());
		$bands = explode('; ', $request->get('bands'));

		if('edit' == $action) {

			$element = $type::find($id);

		} else {

			$element = new $type;
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$element->id = $fill_id;}

		}

		$element->name = $name;
		if(!empty($alt_name)) {$element->alt_name = $alt_name;}
		if(!empty($description)) {$element->description = $this->prepareDescription($description);}
		if(!empty($year)) {$element->year = $year;}
		if(!empty($length)) {$element->length = $length;}

		if(!empty($alt_name)) {
			if (RolesHelper::isAdmin($request)) {$element->verified = 1;} else {$element->verified = 0;}
		}

		$element->save();

		$this->sync($genres, 'genres', $element);
		$this->sync($collections, 'collections', $element);

		$this->sync($writers, 'writers', $element);
		$this->sync($books_publishers, 'books_publishers', $element);

		$this->sync($directors, 'directors', $element);
		$this->sync($screenwriters, 'screenwriters', $element);
		$this->sync($producers, 'producers', $element);
		$this->sync($actors, 'actors', $element);
		$this->sync($countries, 'countries', $element);

		$this->sync($platforms, 'platforms', $element);
		$this->sync($developers, 'developers', $element);
		$this->sync($games_publishers, 'games_publishers', $element);

		$this->sync($bands, 'bands', $element);
		$this->sync($tracks, 'tracks', $element);

		$this->setCover($request, $section, $element->id);

		if('edit' != $action) {
			$this->setUploader($type, $element->id);
		}

		return $this->returnSuccess($section, $element->id);

	}

	/**
	 * @param string $description
	 * @return string
	 */
	private function prepareDescription(string $description) {
		return EMTypograph::fast_apply($description, array(
			'Text.paragraphs' => 'off',
			'Text.breakline' => 'off',
			'OptAlign.all' => 'off',
			'Nobr.super_nbsp' => 'off'
		));
	}

	/**
	 * @param array $list
	 * @param string $entity_section
	 * @param $element
	 * @return bool
	 */
	protected function sync(array $list, string $entity_section, $element) {

		if(!isset($list[0]) || empty($list[0])) {return false;}

		$entities = array();

		$entity_type = SectionsHelper::getSectionType($entity_section);

		foreach ($list as $value) {

			$entity = $entity_type::where('name', '=', $value)->first();

			if (!isset($entity->name)) {

				$entity = new $entity_type;

				$fill_id = $this->getMissingId($entity_section);
				if($fill_id) {$entity->id = $fill_id;}

				$entity->name = $value;
				$entity->description = '';
				$entity->save();

			}

			$entities[] = $entity->id;
			//$element->$entity_section()->attach($entity);

		}

		$element->$entity_section()->sync($entities);

		return true;

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param int $element_id
	 */
	private function setCover(Request $request, string $section, int $element_id) {

		$path = public_path().'/data/img/covers/'.$section;
		$fileName = $element_id.'.jpg';
		$full_path = $path.'/'.$fileName;

		if ($request->hasFile('cover')) {
			if (file_exists($full_path)) {
				unlink($full_path);
			}
			$this->resizeCrop($request->file('cover')->getRealPath(), $full_path);
		}

	}

	/**
	 * @param string $type
	 * @param int $element_id
	 */
	private function setUploader(string $type, int $element_id) {

		$user = Auth::user();
		$uploader = new Uploader();
		$uploader->element_type = $type;
		$uploader->element_id = $element_id;
		$uploader->user_id = $user->id;
		$uploader->save();

	}

	/**
	 * @param string $section
	 * @param int $element_id
	 * @return RedirectResponse
	 */
	private function returnSuccess(string $section = '', int $element_id = 0) {

		return Redirect::to('/'.$section.'/'.$element_id)->with('message', 'Спасибо, элемент отправлен на модерацию');

	}

	/**
	 * @param $real_path
	 * @param $full_path
	 */
	private function resizeCrop($real_path, $full_path) {

		$width = 185 * 2;
		$height = 270 * 2;

		$resize = ResizeCrop::resize($real_path, $full_path, $width, 0);
		$size = getimagesize($full_path);

		/*
		if($height > $size[1]) {
			$diff = ($height - $size[1]) / 2;
			$crop = ResizeCrop::crop($full_path, $full_path, array(0, -$diff, $width, ($height - $diff)));
		}
		*/

	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $id
	 * @return RedirectResponse
	 * @throws Exception
	 */
	public function delete(Request $request, $section, $id)	{

		if(RolesHelper::isAdmin($request)) {
			$section_name = SectionsHelper::getSectionType($section);
			ElementsHelper::deleteElement($id, $section, $section_name);
			return Redirect::to('/'.$section);
		}

		return Redirect::back();

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @return bool|RedirectResponse
	 */
	public function q_add(Request $request, $section) {

		$name = $request->get('new_name', '');
		$template = $request->get('template');
		
		if(!empty($name)) {

			$type = SectionsHelper::getSectionType($section);

			$element = new $type;

			$fill_id = $this->getMissingId($section);
			if($fill_id) {$element->id = $fill_id;}

			$element->name = TextHelper::getCleanName(urldecode($name));
			$element->cover = '';
			$element->description = '';
			$element->year = 0;
			$element->verified = 0;
			$element->save();

			$genres = $collections = $countries = array();

			if(!empty($template)) {

				switch ($template) {

					case 'fiction_book':
						$genres = array('Фантастика и фэнтези');
						break;

					case 'action_book':
						$genres = array('Детективы и боевики');
						break;

					case 'adventure_book':
						$genres = array('Приключения и исторический роман');
						break;

					case 'lovestory_book':
						$genres = array('Любовный роман');
						break;

					case 'novel_book':
						$genres = array('Современная проза');
						break;

					case 'nonfiction_book':
						$genres = array('Публицистика и нон-фикшн');
						break;

					case 'poetry_book':
						$genres = array('Поэзия');
						break;

					case 'comic_book':
						$genres = array('Комиксы и манга');
						break;

					case 'marvel_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('Marvel Comics');
						break;

					case 'dc_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('DC Comics');
						break;

					case 'image_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('Image Comics');
						break;

					case 'valiant_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('Valiant Comics');
						break;

					case 'fiction_film':
						$genres = array('Фантастика');
						break;

					case 'marvel_film':
						$genres = array('Фантастика');
						$collections = array('Marvel Comics');
						break;

					case 'dc_film':
						$genres = array('Фантастика');
						$collections = array('DC Comics');
						break;

					case 'fantasy_film':
						$genres = array('Фэнтези');
						break;

					case 'drama_film':
						$genres = array('Драмы');
						break;

					case 'melodrama_film':
						$genres = array('Мелодрамы');
						break;

					case 'comedy_film':
						$genres = array('Комедии');
						break;

					case 'family_film':
						$genres = array('Семейные');
						break;

					case 'adventure_film':
						$genres = array('Приключения');
						break;

					case 'detective_film':
						$genres = array('Детективы');
						break;

					case 'action_film':
						$genres = array('Экшены');
						break;

					case 'thriller_film':
						$genres = array('Триллеры');
						break;

					case 'horror_film':
						$genres = array('Ужасы');
						break;

					case 'series_film':
						$genres = array('Сериалы');
						break;

					case 'animated_film':
						$genres = array('Мультфильмы');
						break;

					case 'anime':
						$genres = array('Аниме', 'Мультфильмы');
						$countries = array('Япония');
						break;

					case 'action_game':
						$genres = array('Экшен');
						break;

					case 'roleplay_game':
						$genres = array('Ролевые игры');
						break;

					case 'strategy_game':
						$genres = array('Стратегии');
						break;

					case 'quest_game':
						$genres = array('Квесты');
						break;

					case 'arcade_game':
						$genres = array('Аркады');
						break;

					case 'rock_album':
						$genres = array('Rock');
						break;

					case 'rap_album':
						$genres = array('Rap');
						break;

					case 'pop_album':
						$genres = array('Pop');
						break;

					case 'electronic_album':
						$genres = array('Electronic');
						break;

					default:

				}

			}

			$this->sync($genres, 'genres', $element);
			$this->sync($collections, 'collections', $element);
			$this->sync($countries, 'countries', $element);

			$this->setUploader($type, $element->id);

			$user = Auth::user();
			$event = new Event();
			$event->event_type = 'UserAdd';
			$event->element_type = $type;
			$event->element_id = $element->id;
			$event->user_id = $user->id;
			$event->name = $name;
			$event->text = 'Добавлено произведение';
			$event->save();

			return Redirect::to('/'.$section.'/'.$element->id);

		}

		return Redirect::back();

	}

	/**
	 * @param string $section
	 * @return int
	 */
	private function getMissingId(string $section = '') {

		// обратная конвертация из конкретного объекта в общий
		// позже надо будет это как-то переделать
		$type = SectionsHelper::getSectionType($section);
		$section = SectionsHelper::getSectionBy($type);

		$missing_id = 0;

		$query = "SELECT (`".$section."`.`id`+1) as `empty_id`
		FROM `".$section."`
		WHERE (
			SELECT 1 FROM `".$section."` as `st` WHERE `st`.`id` = (`".$section."`.`id` + 1)
		) IS NULL
		ORDER BY `".$section."`.`id`
		LIMIT 1";

		$result = DB::select($query)[0]->empty_id;

		if(!empty($result)) {$missing_id = $result;}

		return $missing_id;

	}

}
