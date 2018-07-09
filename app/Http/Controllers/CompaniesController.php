<?php namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Input;
use View;
use App\Models\Company;

class CompaniesController extends Controller {

	private $prefix = 'companies';

	public function show_all(Request $request) {

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

		$elements = Company::orderBy($sort, $sort_direction)
			->paginate($limit)
		;

		return View::make($this->prefix.'.index', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'ru_section' => $ru_section,
			//'sort_options' => $sort_options,
			//'wanted' => $wanted,
			//'not_wanted' => $not_wanted,
		));

	}

	/*
    public function show_collections()
    {
        return View::make($this->prefix.'.collections');
    }
	
    public function show_collection()
    {
        return View::make($this->prefix.'.collection');
    }
	*/
	
    public function show_item(Request $request, $id) {

    	$section = 'companies';

		$company = Company::find($id);

		if(isset($company->id)) {
			$logo = 0;
			$file_path = public_path() . '/data/img/covers/companies/'.$id.'.jpg';
			if (file_exists($file_path)) {
				$logo = $id;
			}

			$sort = Input::get('sort', 'created_at');
			$sort_direction = Input::get('sort_direction', 'desc');
			$limit = 28;

			$books_published = $company->books_published()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$games_developed = $company->games_developed()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$games_published = $company->games_published()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)

			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'year' => 'Год'
			);

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'section' => $section,
				'element' => $company,
				'cover' => $logo,
				'books_published' => $books_published,
				'games_developed' => $games_developed,
				'games_published' => $games_published,
				'sort_options' => $sort_options
			));
		} else {
			// нет такой буквы
			return Redirect::to('/companies')->with('message', 'Нет такой компании');
		}
    }
	
    public function show_authors()
    {
        return View::make($this->prefix.'.authors');
    }	
	
    public function show_author()
    {
        return View::make($this->prefix.'.author');
    }

	/**
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function transfer(int $id = 0) {

		$recipient_id = Input::get('recipient_id');

		DB::table('developers_games')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));
		DB::table('publishers_games')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));
		DB::table('publishers_books')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));

		DB::table('companies')->where('id', '=', $id)->update(array('name' => ''));

		return Redirect::to('/'.$this->prefix.'/'.$recipient_id);

	}
}