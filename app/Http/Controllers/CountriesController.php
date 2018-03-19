<?php namespace App\Http\Controllers;

use Auth;
use DB;
use View;
use Input;
use Redirect;
use App\Models\Section;
use App\Models\Wanted;
use App\Models\Country;

class CountriesController extends Controller {

	private $prefix = 'films';

    public function show_all()
    {
	    $genres = DB::table($this->prefix);
        return View::make('books.genres', array(
			'books' => $genres
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
			$section.'.alt_name' => 'Оригинальное название',
			$section.'.year' => 'Год'
		);

		$country = Country::find($id);

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

			$elements = $country->$section()->orderBy($sort, $sort_direction)
				->with(array('rates' => function($query) use($user_id, $section, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->whereNotIn($section.'.id', $not_wanted)
				->paginate($limit)
			;
		}
		else
		{
			$elements = $country->$section()->orderBy($sort, $sort_direction)
				->paginate($limit)
			;
		}

        return View::make('films.country', array(
			'country' => $country,
			'films' => $elements,
			'section' => $section,
			'ru_section' => $ru_section,
			'sort_options' => $sort_options
		));
    }
	
    public function show_authors()
    {
        return View::make($this->prefix.'.authors');
    }	
	
    public function show_author()
    {
        return View::make($this->prefix.'.author');
    }
	
}