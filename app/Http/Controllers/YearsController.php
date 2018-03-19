<?php namespace App\Http\Controllers;

use Auth;
use DB;
use View;
use Input;
use Redirect;
use App\Models\Book;
use App\Models\Film;
use App\Models\Game;
use App\Models\Album;
use App\Models\Section;
use App\Models\Helpers;
use App\Models\Wanted;

class YearsController extends Controller {

	//private $prefix = 'persons';

    public function show_all()
    {
	    $persons = DB::table($this->prefix)->paginate(27);
		$photos = array();
		$default_photo = 0;
		foreach($persons as $person)
		{
			$file_path = $_SERVER['DOCUMENT_ROOT'].'data/img/covers/'.$this->prefix.'/'.$person->id.'.jpg';
			//echo $file_path.'<br/>';
			if(file_exists($file_path))
			{
				$photos[$person->id] = $person->id;
			}
			else
			{
				$covers[$person->id] = $default_photo;
			}
		}

        return View::make($this->prefix.'.index', array(
			'persons' => $persons,
			'photos' => $photos
		));
    }
	
    public function show_item($section, $year)
    {
		//$get_section = Section::where('alt_name', '=', $section)->first();
		$ru_section = Helpers::get_section_name($section);
		$type = Helpers::get_section_type($section);
		//die($type);

		$sort = Input::get('sort', $section.'.created_at');
		$sort_direction = Input::get('sort_direction', 'desc');
		$limit = 28;

		$sort_options = array(
			$section.'.created_at' => 'Время добавления',
			$section.'.name' => 'Название',
			$section.'.alt_name' => 'Оригинальное название',
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

			//die($section);
			$elements = $type::orderBy($sort, $sort_direction)
				->with(array('rates' => function($query) use($user_id, $section, $type)
					{
						$query
							->where('user_id', '=', $user_id)
							->where('element_type', '=', $type)
						;
					})
				)
				->whereNotIn($section.'.id', $not_wanted)
				->where('year', '=', $year)
				->paginate($limit)
				//->toSql()
			;
			//die($elements);
		}
		else
		{
			$elements = $type::orderBy($sort, $sort_direction)
				->where('year', '=', $year)
				->paginate($limit)
			;
		}

		if(!empty($elements)) {
			return View::make('years.item', array(
				'year' => $year,
				'elements' => $elements,
				'section' => $section,
				'ru_section' => $ru_section,
				'sort_options' => $sort_options
			));
		}
    }
	
}