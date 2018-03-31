<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Input;
use View;
use App\Models\Company;

class CompaniesController extends Controller {

	private $prefix = 'companies';

    public function show_all() {

		/*
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
		*/
		return Redirect::home()->with('message', 'Полный список компаний в разработке');
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
	
    public function show_item(Request $request, $id)
    {
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
				'company' => $company,
				'company_logo' => $logo,
				'books_published' => $books_published,
				'games_developed' => $games_developed,
				'games_published' => $games_published,
				'sort_options' => $sort_options
			));
		}
		else
		{
			// нет такой буквы
			return Redirect::home()->with('message', 'Нет такой компании');
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