<?php namespace App\Http\Controllers;

use Auth;
use Input;
use View;
use Redirect;
use App\Models\Section;
use App\Models\Helpers;
use App\Models\Album;
use App\Models\Wanted;
use App\Models\ElementRelation;

class AlbumsController extends Controller {

	private $prefix = 'albums';

	private $limit = 28;

    public function show_all()
    {
		$section = $this->prefix;
		$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = $get_section->name;
		$type = $get_section->type;

		$sort = Input::get('sort', $section.'.created_at');
		$sort_direction = Input::get('sort_direction', 'desc');
		$limit = 28;

		$sort_options = array(
			$section.'.created_at' => 'Время добавления',
			$section.'.name' => 'Название',
			//$section.'.alt_name' => 'Оригинальное название',
			$section.'.year' => 'Год'
		);

		if(Auth::check())
		{
			$user_id = Auth::user()->id;
			$not_wanted = Wanted::select('element_id')
				->where('element_type', '=', $type)
				->where('not_wanted', '=', 1)
				->where('user_id', '=', $user_id)
				//->remember(10)
				->pluck('element_id')
			;

			$elements = Album::orderBy($sort, $sort_direction)
				->with(array('rates' => function($query)
					{
						$query
							->where('user_id', '=', Auth::user()->id)
							->where('element_type', '=', 'Album')
						;
					})
				)
				->whereNotIn('id', $not_wanted)
				->paginate($limit)
			;
		}
		else
		{
			$elements = Album::orderBy($sort, $sort_direction)
				->paginate($limit)
			;
		}

		return View::make($this->prefix.'.index', array(
			'elements' => $elements,
			'section' => $section,
			'ru_section' => $ru_section,
			'sort_options' => $sort_options
		));
    }

    public function show_collections()
    {
        return View::make($this->prefix.'.collections');
    }

    public function show_collection()
    {
        return View::make($this->prefix.'.collection');
    }
	
    public function show_item($id)
	{
		$album = Album::find($id);

		if(count($album)) {
			$tracks = $album->tracks;
			//$publishers = $album->publisher;
			$bands = $album->bands()->orderBy('name')->get();
			$genres = $album->genres; $genres = $genres->sortBy('name')->reverse();
			$collections = $album->collections;

			//die('<pre>'.print_r($genres, true).'</pre>');
			//die('<pre>'.print_r($platforms, true).'</pre>');

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
			$wanted = 0;
			$not_wanted = 0;
			if (Auth::check()) {
				$user_id = Auth::user()->id;
				$rate = $album->rates()->where('user_id', '=', $user_id)->first();
				if (isset($rate->rate)) {
					$user_rate = $rate->rate;
				}

				$wanted_album = $album
					->wanted()
					->where('user_id', '=', $user_id)
					->first();
				if (isset($wanted_album->id)) {
					$wanted = $wanted_album->wanted;
					$not_wanted = $wanted_album->not_wanted;
				}
			}

			$cover = 0;
			$file_path = public_path() . '/data/img/covers/albums/' . $id . '.jpg';
			if (file_exists($file_path)) {
				$cover = $id;
			}

			$section = $this->prefix;

			$rating = Helpers::count_rating($album);
			
			$section_type = 'Album';
			$relations = ElementRelation::where('to_id', '=', $id)
				->where('element_type', '=', $section_type)
				->count()
			;

			$sim_options['type'] = 'Album';
			$sim_options['genres'] = $genres;
			$sim_limit = 3;

			for($i = 0; $i < $sim_limit; $i++) {
				$similar[] = Helpers::get_similar($sim_options);
			}

			return View::make($this->prefix . '.item', array(
				'album' => $album,
				'tracks' => $tracks,
				//'publishers' => $publishers,
				'bands' => $bands,
				'genres' => $genres,
				'collections' => $collections,
				'cover' => $cover,
				'rate' => $user_rate,
				'wanted' => $wanted,
				'not_wanted' => $not_wanted,
				'comments' => $comments,
				'section' => $section,
				'rating' => $rating,
				'relations' => $relations,
				'similar' => collect($similar)
			));
		}
		else {
			return Redirect::to('/base/albums/');
		}
	}
	
}