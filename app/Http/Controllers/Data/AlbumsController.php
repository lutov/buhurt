<?php namespace App\Http\Controllers\Data;

use App\Helpers\ElementsHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\User\Unwanted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Data\Album;
use App\Models\User\Wanted;
use App\Models\Search\ElementRelation;

class AlbumsController extends Controller {

	private $prefix = 'albums';

    public function list(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		$sort = $request->get('sort', 'created_at');
		$order = $request->get('order', 'desc');
		$limit = 28;

		$sort_options = array(
			$section.'.created_at' => 'Время добавления',
			$section.'.name' => 'Название',
			//$section.'.alt_name' => 'Оригинальное название',
			$section.'.year' => 'Год'
		);

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$wanted = array();
		$unwanted = array();

		if(Auth::check()) {

			$user_id = Auth::user()->id;

			$wanted = Wanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('user_id', '=', $user_id)
				->pluck('element_id')
				->toArray()
			;

			$unwanted = Unwanted::select('element_id')
				->where('element_type', '=', $section->type)
				->where('user_id', '=', $user_id)
				->pluck('element_id')
				->toArray()
			;

			$elements = Album::where('verified', '=', 1)
				->whereNotIn('id', $unwanted)
				->with(array('rates' => function($query)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', 'Album')
						;
					})
				)
				->orderBy($sort, $order)
				->paginate($limit)
			;
		} else {
			$elements = Album::where('verified', '=', 1)
				->orderBy($sort, $order)
				->paginate($limit)
			;
		}

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'wanted' => $wanted,
			'unwanted' => $unwanted,
			'sort_list' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make($this->prefix.'.index', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'options' => $options,
		));
    }

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
    public function item(Request $request, $id) {

		$album = Album::find($id);

		if(count($album)) {
			
			$tracks = $album->tracks()->orderBy('order')->get();
			$bands = $album->bands()->orderBy('name')->get();
			$genres = $album->genres; $genres = $genres->sortBy('name')->reverse();
			$collections = $album->collections;

			if(Auth::check()) {

				$user = Auth::user();
				$user_options = $user
					->options()
					->where('enabled', '=', 1)
					->pluck('option_id')
					->toArray();

				$is_other_private = in_array(2, $user_options);

				if($is_other_private) {

					$comments = $album->comments()
						->with('user')
						->where('user_id', '=', $user->id)
						->orderBy('created_at', 'desc')
						->get();

				} else {

					$comments = $album->comments()
						->with('user')
						->orderBy('created_at', 'desc')
						->get();

				}

			} else {

				$comments = $album->comments()
					->with('user')
					->orderBy('created_at', 'desc')
					->get()
				;

			}

			$user_rate = 0;

			if (Auth::check()) {

				$user_id = Auth::user()->id;
				$rate = $album->rates()->where('user_id', '=', $user_id)->first();
				if (isset($rate->rate)) {
					$user_rate = $rate->rate;
				}

			}

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/albums/' . $id . '.jpg';
			if (file_exists($file_path)) {
				$cover = $id;
			}

			$section = SectionsHelper::getSection($this->prefix);

			$rating = ElementsHelper::countRating($album);
			
			$section_type = 'Album';
			$relations = ElementRelation::where('to_id', '=', $id)
				->where('to_type', '=', $section_type)
				->count()
			;

			$sim_options['element_id'] = $id;
			$sim_options['type'] = 'Album';
			$sim_options['genres'] = $genres;
			$sim_limit = 3;

			$similar = array();
			for($i = 0; $i < $sim_limit; $i++) {
				$similar[] = ElementsHelper::getSimilar($sim_options);
			}

			$options = array(
				'rate' => $user_rate,
				'genres' => $genres,
				'cover' => $cover,
				'similar' => collect($similar),
				'collections' => $collections,
				'relations' => $relations,
				'bands' => $bands,
				'tracks' => $tracks,
			);

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'element' => $album,
				'comments' => $comments,
				'section' => $section,
				'rating' => $rating,
				'options' => $options,
			));

		} else {

			return Redirect::to('/albums/');

		}
	}

	/**
	 * @param Request $request
	 * @param int $id
	 * @return mixed
	 * @throws \Exception
	 */
	public function transfer(Request $request, int $id = 0) {

		$section = $this->prefix;
		$type = 'Album';

		$recipient_id = $request->get('recipient_id');

		$element_rate = DB::table('rates')
			->where('element_type', '=', $type)
			->where('element_id', '=', $recipient_id)
			->get()
			->toArray()
		;

		//echo Helpers\DebugHelper::dump($element_rate); die();

		if(!isset($element_rate[0]->id)) {

			DB::table('rates')
				->where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->update(array('element_id' => $recipient_id))
			;

		}

		ElementsHelper::deleteElement($id, $section, $type);

		return Redirect::to('/'.$this->prefix.'/'.$recipient_id);

	}
	
}