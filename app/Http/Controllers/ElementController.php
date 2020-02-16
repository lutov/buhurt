<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 16.02.2020
 * Time: 13:13
 */

namespace App\Http\Controllers;

use App\Helpers\CommentsHelper;
use App\Helpers\ElementsHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Helpers\UserHelper;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ElementController extends Controller {

	protected string $section;
	protected bool $getSimilar = true;

	public function list(Request $request) {

		$section = SectionsHelper::getSection($this->section);

		$sort = $request->get('sort', 'created_at');
		$order = $request->get('order', 'desc');
		$limit = 28;

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Название',
			'alt_name' => 'Оригинальное название',
			'year' => 'Год'
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		if(Auth::check()) {

			$user = Auth::user();

			$unwanted = ElementsHelper::getUnwanted($section, $user->id);

			$elements = $section->type::where('verified', '=', 1)
				->whereNotIn('id', $unwanted)
				->orderBy($sort, $order)
				->paginate($limit)
			;

		} else {

			$elements = $section->type::where('verified', '=', 1)
				->orderBy($sort, $order)
				->paginate($limit)
			;
		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'sort_options' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make($this->section.'.index', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'options' => $options,
		));

	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function item(Request $request, $id) {

		$section = SectionsHelper::getSection($this->section);
		$element = $section->type::find($id);

		if(!empty($element)) {

			if(Auth::check()) {
				$user = Auth::user();
				$user_options = UserHelper::getOptions($user);
				$is_other_private = in_array(2, $user_options);
				$comments = ($is_other_private) ? CommentsHelper::get($element, $user->id) : CommentsHelper::get($element);
			} else {
				$comments = CommentsHelper::get($element);
			}

			$rating = ElementsHelper::countRating($element);

			$similar = array();
			if($this->getSimilar) {
				$genres = $element->genres; $genres = $genres->sortBy('name');
				$sim_options['element_id'] = $id;
				$sim_options['type'] = $section->type;
				$sim_options['genres'] = $genres;
				$sim_limit = 3;
				for ($i = 0; $i < $sim_limit; $i++) {
					$similar[] = ElementsHelper::getSimilar($sim_options);
				}
			}

			$options = array(
				'similar' => collect($similar),
			);

			return View::make($this->section.'.item', array(
				'request' => $request,
				'section' => $section,
				'element' => $element,
				'rating' => $rating,
				'comments' => $comments,
				'options' => $options,
			));

		} else {

			return Redirect::to('/'.$this->section);

		}

	}

	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getJson($id) {
		$section = SectionsHelper::getSection($this->section);
		$element = $section->type::find($id);
		$genres = $element->genres; $genres = $genres->sortBy('name');
		$rating = ElementsHelper::countRating($element);
		$similar = array();
		$sim_options['type'] = $section->type;
		$sim_options['genres'] = $genres;
		$sim_limit = 0;
		for($i = 0; $i < $sim_limit; $i++) {
			$similar[] = ElementsHelper::getSimilar($sim_options);
		}
		return View::make($this->section.'.json', array(
			'element' => $element,
			'section' => $section,
			'rating' => $rating,
			'similar' => collect($similar)
		));
	}

	/**
	 * @param Request $request
	 * @param int $id
	 * @return RedirectResponse
	 * @throws Exception
	 */
	public function transfer(Request $request, int $id) {

		$section = SectionsHelper::getSection($this->section);

		$recipient_id = $request->get('recipient_id');

		ElementsHelper::transfer($section, $id, $recipient_id);

		return Redirect::to('/'.$section->name.'/'.$recipient_id);

	}

}