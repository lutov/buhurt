<?php namespace App\Http\Controllers;

use DB;
use View;
use Input;
use Redirect;
use App\Models\Band;

class BandsController extends Controller {

	private $prefix = 'bands';

    public function show_all()
    {
	    $bands = DB::table($this->prefix)->paginate(27);
		$photos = array();
		$default_photo = 0;
		foreach($bands as $band)
		{
			$file_path = $_SERVER['DOCUMENT_ROOT'].'data/img/covers/'.$this->prefix.'/'.$band->id.'.jpg';
			//echo $file_path.'<br/>';
			if(file_exists($file_path))
			{
				$photos[$band->id] = $band->id;
			}
			else
			{
				$covers[$band->id] = $default_photo;
			}
		}

        return View::make($this->prefix.'.index', array(
			'bands' => $bands,
			'photos' => $photos
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
		$band = Band::find($id);

		if(isset($band->id)) {
			$photo = 0;
			$file_path = public_path() . '/data/img/covers/bands/'.$id.'.jpg';
			if (file_exists($file_path)) {
				$photo = $id;
			}

			$sort = Input::get('sort', 'created_at');
			$sort_direction = Input::get('sort_direction', 'desc');
			$limit = 28;

			$albums = $band->albums()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			$members = $band->members()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			//$screenplays = $band->screenplays()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			//$productions = $band->productions()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)
			//$actions = $band->actions()->orderBy('created_at', $sort_direction)->paginate($limit); //->remember(60)

			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'year' => 'Год'
			);

			return View::make($this->prefix . '.item', array(
				'band' => $band,
				'photo' => $photo,
				'albums' => $albums,
				'members' => $members,
				//'actions' => $actions,
				//'screenplays' => $screenplays,
				//'productions' => $productions,
				'sort_options' => $sort_options
			));
		}
		else
		{
			// нет такой буквы
			return Redirect::home()->with('message', 'Нет такой персоны');
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
	
}