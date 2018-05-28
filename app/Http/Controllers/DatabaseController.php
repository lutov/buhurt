<?php namespace App\Http\Controllers;

use App\Models\Helpers\RolesHelper;
use App\Models\Helpers\SectionsHelper;
use DB;
use Auth;
use Illuminate\Http\Request;
use View;
use Input;
use Validator;
use Redirect;
use EMTypograph;
use ResizeCrop;
use App\Models\Book;
use App\Models\Film;
use App\Models\Game;
use App\Models\Band;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Track;
use App\Models\Person;
use App\Models\Helpers;
use App\Models\Country;
use App\Models\Company;
use App\Models\Uploader;
use App\Models\Platform;
use App\Models\Collection;
use App\Models\ElementGenre;
use App\Models\ElementCollection;

class DatabaseController extends Controller {

	public function add(Request $request, $section = '') {

		switch($section) {
			case 'books':
				$genres = Genre::where('element_type', '=', 'Book')->orderBy('name')->get(); //->remember(60)
				$countries = array();
				$platforms = array();
				break;

			case 'films':
				$genres = Genre::where('element_type', '=', 'Film')->orderBy('name')->get(); //->remember(60)
				$countries = Country::orderBy('name')->get(); //->remember(60)
				$platforms = array();
				break;

			case 'games':
				$genres = Genre::where('element_type', '=', 'Game')->orderBy('name')->get(); //->remember(60)
				$platforms = Platform::orderBy('name')->get(); //->remember(60)
				$countries = array();
				break;

			case 'albums':
				$genres = Genre::where('element_type', '=', 'Album')->orderBy('name')->get(); //->remember(60)
				$platforms = array();
				$countries = array();
				break;

			default:
				$section = 'films';
				$genres = Genre::where('element_type', '=', 'Film')->orderBy('name')->get(); //->remember(60)
				$countries = Country::orderBy('name')->get(); //->remember(60)
				$platforms = array();
				break;
		}

		$collections = Collection::orderBy('name')
			//->remember(20)
			->get();

		return View::make('database.add', array(
			'request' => $request,
			'section' => $section,
			'genres' => $genres,
			'platforms' => $platforms,
			'countries' => $countries,
			'collections' => $collections
		));
	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $id
	 * @return \Illuminate\Contracts\View\View
	 */
	public function edit(Request $request, $section, $id) {

		//$section = Input::get('section');
		//$id = Input::get('id');

		switch($section) {

			case 'books':
				$element = Book::find($id);
				$genres = Genre::where('element_type', '=', 'Book')->orderBy('name')->get(); //->remember(60)
				$countries = array();
				$platforms = array();
				break;

			case 'films':
				$element = Film::find($id); //die(print_r($element));
				$genres = Genre::where('element_type', '=', 'Film')->orderBy('name')->get(); //->remember(60)
				$countries = Country::orderBy('name')->get(); //->remember(60)
				$platforms = array();
				break;

			case 'games':
				$element = Game::find($id);
				$genres = Genre::where('element_type', '=', 'Game')->orderBy('name')->get(); //->remember(60)
				$platforms = Platform::orderBy('name')->get(); //->remember(60)
				$countries = array();
				break;

			case 'albums':
				$element = Album::find($id);
				$genres = Genre::where('element_type', '=', 'Album')->orderBy('name')->get(); //->remember(60)
				$platforms = array();
				$countries = array();
				break;

			default:
				$element = array();
				$genres = array();
				$countries = array();
				$platforms = array();
				break;

		}

		$collections = Collection::orderBy('name')
			//->remember(20)
			->get();

		$default_cover = 0;
		$file_path = public_path() . '/data/img/covers/' . $section . '/' . $id . '.jpg';
		if (file_exists($file_path)) {
			$element_cover = $id;
		} else {
			$element_cover = $default_cover;
		}

		return View::make('database.edit', array(
			'request' => $request,
			'section' => $section,
			'element' => $element,
			'element_cover' => $element_cover,
			'genres' => $genres,
			'platforms' => $platforms,
			'countries' => $countries,
			'collections' => $collections
		));
	}

	public function save() {

		//die('<pre>'.print_r($_POST, true));

		$section = Input::get('section');

		if('books' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'book_name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		}
		elseif('films' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'film_name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		}
		elseif('games' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'game_name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		} elseif('albums' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'album_name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		}
		else
		{
			$validator = new Validator();
		}

		if ($validator->fails())
		{
			// Переданные данные не прошли проверку.
			/*
			$messages = $validator->messages();

			foreach ($messages->all() as $message)
			{
				//
			}
			*/

			return Redirect::back()->withInput()->withErrors($validator);
		}
		else
		{
			if('books' == $section) {
				$type = 'Book';

				$name = Input::get('book_name');
				$alt_name = Input::get('book_alt_name');
				$description = Input::get('book_description');
				$writers = explode('; ', Input::get('book_writer'));
				$genres = explode('; ', Input::get('book_genre')); //die(print_r($genres));
				$collections = explode('; ', Input::get('collections'));
				$year = Input::get('book_year');
				$publishers = explode('; ', Input::get('book_publisher'));

				// general
				$action = Input::get('action', '');
				if('edit' == $action)
				{
					$id = Input::get('element_id');
					$book = Book::find($id);

					DB::table('elements_genres')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('elements_collections')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('writers_books')
						->where('book_id', '=', $id)
						->delete();

					DB::table('publishers_books')
						->where('book_id', '=', $id)
						->delete();
				}
				else
				{
					$book = new Book();
				}
				$book->name = $name;
				$book->alt_name = $alt_name;
				$book->description = EMTypograph::fast_apply($description, array(
					'Text.paragraphs' => 'off',
					'Text.breakline' => 'off',
					'OptAlign.all' => 'off',
					'Nobr.super_nbsp' => 'off'
				));
				$book->year = $year;
				$book->verified = 0; // пометка о необходимости модерации
				$book->save();

				// writers
				if('' != $writers[0]) {
					foreach ($writers as $writer) {
						$existing_writer = Person::where('name', '=', $writer)->first();
						if (isset($existing_writer->name)) {
							$existing_writer->books()->save($book);
						} else {
							$new_writer = new Person();
							$new_writer->name = $writer;
							$new_writer->bio = '';
							$new_writer->save();

							$new_writer->books()->save($book);
						}
					}
				}

				// genres
				if('' != $genres[0]) {
					//die(print_r($genres));
					foreach ($genres as $genre) {
						$element_genre = new ElementGenre();
						$existing_genre = Genre::where('name', '=', $genre)
							->where('element_type', '=', $type)
							->first();
						if (isset($existing_genre->name)) {
							$element_genre->element_type = $type;
							$element_genre->genre_id = $existing_genre->id;
							$element_genre->element_id = $book->id;
							$element_genre->save();
						} else {
							$new_genre = new Genre();
							$new_genre->name = $genre;
							$new_genre->element_type = $type;
							
							$new_genre->save();

							$element_genre->element_type = $type;
							$element_genre->genre_id = $new_genre->id;
							$element_genre->element_id = $book->id;
							$element_genre->save();
						}
					}
				}

				// collections
				if('' != $collections[0]) {
					//die(print_r($collections));
					foreach ($collections as $collection) {
						$element_collection = new ElementCollection();
						$existing_collection = Collection::where('name', '=', $collection)
							->first();
						if (isset($existing_collection->name)) {
							$element_collection->element_type = $type;
							$element_collection->collection_id = $existing_collection->id;
							$element_collection->element_id = $book->id;
							$element_collection->save();
						} else {
							$new_collection = new Collection();
							$new_collection->name = $collection;
							$new_collection->description = '';

							$new_collection->save();

							$element_collection->element_type = $type;
							$element_collection->collection_id = $new_collection->id;
							$element_collection->element_id = $book->id;
							$element_collection->save();
						}
					}
				}

				// publishers
				if('' != $publishers[0]) {
					foreach ($publishers as $publisher) {
						$existing_publisher = Company::where('name', '=', $publisher)->first();
						if (isset($existing_publisher->name)) {
							$existing_publisher->books_published()->save($book);
						} else {
							$new_publisher = new Company();
							$new_publisher->name = $publisher;
							$new_publisher->description = '';
							$new_publisher->save();

							$new_publisher->books_published()->save($book);
						}
					}
				}

				// file
				$path = public_path() . '/data/img/covers/' . $section;
				//die($path);
				$fileName = $book->id . '.jpg';
				$full_path = $path.'/'.$fileName;
				//die($full_path);

				if (Input::hasFile('cover')) {

					if (file_exists($full_path)) {
						//$element_cover = $id;
						unlink($full_path);
					} else {
						//$element_cover = $default_cover;
					}

					//Input::file('cover')->move($path, $fileName);
					$this->resize_crop(Input::file('cover')->getRealPath(), $full_path);
				}
				//else{die('no file!');}

				if('edit' != $action) {
					// uploader
					$user = Auth::user();
					$uploader = new Uploader();
					$uploader->element_type = $type;
					$uploader->element_id = $book->id;
					$uploader->user_id = $user->id;
					$uploader->save();
				}

				//print_r($book);
				return Redirect::to('/books/' . $book->id)->with('message', 'Спасибо, элемент отправлен на модерацию');
			}
			if('films' == $section) {
				$type = 'Film';

				$name = Input::get('film_name');
				$alt_name = Input::get('film_alt_name');
				$description = Input::get('film_description');
				$directors = explode('; ', Input::get('film_director'));
				$producers = explode('; ', Input::get('film_producer'));
				$screenwriters = explode('; ', Input::get('film_screenwriter'));
				$genres = explode('; ', Input::get('film_genre'));
				$collections = explode('; ', Input::get('collections'));
				$countries = explode('; ', Input::get('film_country'));
				$length = Input::get('film_length');
				$year = Input::get('film_year');
				$actors = explode('; ', Input::get('film_actors'));

				$action = Input::get('action', '');

				// general
				if('edit' == $action)
				{
					$id = Input::get('element_id');
					$film = Film::find($id);

					DB::table('elements_genres')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('elements_collections')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('directors_films')
						->where('film_id', '=', $id)
						->delete();

					DB::table('countries_films')
						->where('film_id', '=', $id)
						->delete();

					DB::table('producers_films')
						->where('film_id', '=', $id)
						->delete();

					DB::table('screenwriters_films')
						->where('film_id', '=', $id)
						->delete();

					DB::table('actors_films')
						->where('film_id', '=', $id)
						->delete();
				}
				else
				{
					$film = new Film();
				}
				$film->name = $name;
				$film->alt_name = $alt_name;
				$film->description = EMTypograph::fast_apply($description, array(
					'Text.paragraphs' => 'off',
					'Text.breakline' => 'off',
					'OptAlign.all' => 'off',
					'Nobr.super_nbsp' => 'off'
				));
				$film->year = $year;
				$film->length = $length;
				$film->verified = 0; // пометка о необходимости модерации
				$film->save();

				// director
				if('' != $directors[0]) {
					foreach ($directors as $director) {
						$existing_director = Person::where('name', '=', $director)->first();
						if (isset($existing_director->name)) {
							$existing_director->directions()->save($film);
						} else {
							$new_director = new Person();
							$new_director->name = $director;
							$new_director->bio = '';
							$new_director->save();

							$new_director->directions()->save($film);
						}
					}
				}

				// screenwriters
				if('' != $screenwriters[0]) {
					foreach ($screenwriters as $screenwriter) {
						$existing_screenwriter = Person::where('name', '=', $screenwriter)->first();
						if (isset($existing_screenwriter->name)) {
							$existing_screenwriter->screenplays()->save($film);
						} else {
							$new_screenwriter = new Person();
							$new_screenwriter->name = $screenwriter;
							$new_screenwriter->bio = '';
							$new_screenwriter->save();

							$new_screenwriter->screenplays()->save($film);
						}
					}
				}

				// producer
				if('' != $producers[0]) {
					foreach ($producers as $producer) {
						$existing_producer = Person::where('name', '=', $producer)->first();
						if (isset($existing_producer->name)) {
							$existing_producer->productions()->save($film);
						} else {
							$new_producer = new Person();
							$new_producer->name = $producer;
							$new_producer->bio = '';
							$new_producer->save();

							$new_producer->productions()->save($film);
						}
					}
				}

				// actor
				if('' != $actors[0]) {
					foreach ($actors as $actor) {
						$existing_actor = Person::where('name', '=', $actor)->first();
						if (isset($existing_actor->name)) {
							$existing_actor->actions()->save($film);
						} else {
							$new_actor = new Person();
							$new_actor->name = $actor;
							$new_actor->bio = '';
							$new_actor->save();

							$new_actor->actions()->save($film);
						}
					}
				}

				// genres
				if('' != $genres[0]) {
					foreach ($genres as $genre) {
						$element_genre = new ElementGenre();
						$existing_genre = Genre::where('name', '=', $genre)
							->where('element_type', '=', $type)
							->first();
						if (isset($existing_genre->name)) {
							$element_genre->element_type = $type;
							$element_genre->genre_id = $existing_genre->id;
							$element_genre->element_id = $film->id;
							$element_genre->save();
						} else {
							$new_genre = new Genre();
							$new_genre->name = $genre;
							$new_genre->element_type = $type;
							$new_genre->save();

							$element_genre->element_type = $type;
							$element_genre->genre_id = $new_genre->id;
							$element_genre->element_id = $film->id;
							$element_genre->save();
						}
					}
				}

				// collections
				if('' != $collections[0]) {
					//die(print_r($collections));
					foreach ($collections as $collection) {
						$element_collection = new ElementCollection();
						$existing_collection = Collection::where('name', '=', $collection)
							->first();
						if (isset($existing_collection->name)) {
							$element_collection->element_type = $type;
							$element_collection->collection_id = $existing_collection->id;
							$element_collection->element_id = $film->id;
							$element_collection->save();
						} else {
							$new_collection = new Collection();
							$new_collection->name = $collection;
							$new_collection->description = '';

							$new_collection->save();

							$element_collection->element_type = $type;
							$element_collection->collection_id = $new_collection->id;
							$element_collection->element_id = $film->id;
							$element_collection->save();
						}
					}
				}

				// countries
				if('' != $countries[0]) {
					foreach ($countries as $country) {
						$existing_country = Country::where('name', '=', $country)->first();
						if (isset($existing_country->name)) {
							$existing_country->films()->save($film);
						} else {
							$new_country = new Country();
							$new_country->name = $country;
							$new_country->save();

							$new_country->films()->save($film);
						}
					}
				}

				// file
				$path = public_path() . '/data/img/covers/' . $section;
				//die($path);
				$fileName = $film->id . '.jpg';
				$full_path = $path.'/'.$fileName;
				//die($full_path);

				if (Input::hasFile('cover')) {

					if (file_exists($full_path)) {
						//$element_cover = $id;
						unlink($full_path);
						//die('unlinked: '.$full_path);
					} else {
						//$element_cover = $default_cover;
						//die($full_path);
					}

					//Input::file('cover')->move($path, $fileName);
					$this->resize_crop(Input::file('cover')->getRealPath(), $full_path);
				}
				//else{die('no file!');}

				if('edit' != $action) {
					// uploader
					$user = Auth::user();
					$uploader = new Uploader();
					$uploader->element_type = $type;
					$uploader->element_id = $film->id;
					$uploader->user_id = $user->id;
					$uploader->save();
				}

				//print_r($book);
				return Redirect::to('/films/' . $film->id)->with('message', 'Спасибо, элемент отправлен на модерацию');
			}
			if('games' == $section) {
				$type = 'Game';

				$name = Input::get('game_name');
				$alt_name = Input::get('game_alt_name');
				$description = Input::get('game_description');
				$platforms = explode('; ', Input::get('game_platform'));
				$genres = explode('; ', Input::get('game_genre'));
				$collections = explode('; ', Input::get('collections'));
				$developers = explode('; ', Input::get('game_developer'));
				$publishers = explode('; ', Input::get('game_publisher'));
				$year = Input::get('game_year');

				// general
				$action = Input::get('action', '');
				if('edit' == $action)
				{
					$id = Input::get('element_id');
					$game = Game::find($id);

					DB::table('elements_genres')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('elements_collections')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('platforms_games')
						->where('game_id', '=', $id)
						->delete();

					DB::table('developers_games')
						->where('game_id', '=', $id)
						->delete();

					DB::table('publishers_games')
						->where('game_id', '=', $id)
						->delete();
				}
				else
				{
					$game = new Game();
				}
				$game->name = $name;
				$game->alt_name = $alt_name;
				$game->description = EMTypograph::fast_apply($description, array(
					'Text.paragraphs' => 'off',
					'Text.breakline' => 'off',
					'OptAlign.all' => 'off',
					'Nobr.super_nbsp' => 'off'
				));
				$game->year = $year;
				$game->verified = 0; // пометка о необходимости модерации
				$game->save();

				// platforms
				if('' != $platforms[0]) {
					foreach ($platforms as $platform) {
						$existing_platform = Platform::where('name', '=', $platform)->first();
						if (isset($existing_platform->name)) {
							$existing_platform->games()->save($game);
						} else {
							$new_platform = new Platform();
							$new_platform->name = $platform;
							$new_platform->save();

							$new_platform->games()->save($game);
						}
					}
				}

				// developers
				if('' != $developers[0]) {
					foreach ($developers as $developer) {
						$existing_developer = Company::where('name', '=', $developer)->first();
						if (isset($existing_developer->name)) {
							$existing_developer->games_developed()->save($game);
						} else {
							$new_developer = new Company();
							$new_developer->name = $developer;
							$new_developer->description = '';
							$new_developer->save();

							$new_developer->games_developed()->save($game);
						}
					}
				}

				// publishers
				if('' != $publishers[0]) {
					foreach ($publishers as $publisher) {
						$existing_publisher = Company::where('name', '=', $publisher)->first();
						if (isset($existing_publisher->name)) {
							$existing_publisher->games_published()->save($game);
						} else {
							$new_publisher = new Company();
							$new_publisher->name = $publisher;
							$new_publisher->description = '';
							$new_publisher->save();

							$new_publisher->games_published()->save($game);
						}
					}
				}

				// genres
				if('' != $genres[0]) {
					foreach ($genres as $genre) {
						$element_genre = new ElementGenre();
						$existing_genre = Genre::where('name', '=', $genre)
							->where('element_type', '=', $type)
							->first();
						if (isset($existing_genre->name)) {
							$element_genre->element_type = $type;
							$element_genre->genre_id = $existing_genre->id;
							$element_genre->element_id = $game->id;
							$element_genre->save();
						} else {
							$new_genre = new Genre();
							$new_genre->name = $genre;
							$new_genre->element_type = $type;
							$new_genre->save();

							$element_genre->element_type = $type;
							$element_genre->genre_id = $new_genre->id;
							$element_genre->element_id = $game->id;
							$element_genre->save();
						}
					}
				}

				// collections
				if('' != $collections[0]) {
					//die(print_r($collections));
					foreach ($collections as $collection) {
						$element_collection = new ElementCollection();
						$existing_collection = Collection::where('name', '=', $collection)
							->first();
						if (isset($existing_collection->name)) {
							$element_collection->element_type = $type;
							$element_collection->collection_id = $existing_collection->id;
							$element_collection->element_id = $game->id;
							$element_collection->save();
						} else {
							$new_collection = new Collection();
							$new_collection->name = $collection;
							$new_collection->description = '';

							$new_collection->save();

							$element_collection->element_type = $type;
							$element_collection->collection_id = $new_collection->id;
							$element_collection->element_id = $game->id;
							$element_collection->save();
						}
					}
				}

				// file
				$path = public_path() . '/data/img/covers/' . $section;
				//die($path);
				$fileName = $game->id . '.jpg';
				$full_path = $path.'/'.$fileName;
				//die($full_path);

				if (Input::hasFile('cover')) {

					if (file_exists($full_path)) {
						//$element_cover = $id;
						unlink($full_path);
					} else {
						//$element_cover = $default_cover;
					}

					//Input::file('cover')->move($path, $fileName);
					$this->resize_crop(Input::file('cover')->getRealPath(), $full_path);
				}
				//else{die('no file!');}

				if('edit' != $action) {
					// uploader
					$user = Auth::user();
					$uploader = new Uploader();
					$uploader->element_type = $type;
					$uploader->element_id = $game->id;
					$uploader->user_id = $user->id;
					$uploader->save();
				}

				//print_r($book);
				return Redirect::to('/games/'.$game->id)->with('message', 'Спасибо, элемент отправлен на модерацию');
			}
			if('albums' == $section) {
				$type = 'Album';

				$name = Input::get('album_name');
				//$alt_name = Input::get('game_alt_name');
				$tracks = Input::get('tracks');
				$description = Input::get('album_description', ' ');
				$bands = explode('; ', Input::get('album_band'));
				$genres = explode('; ', Input::get('album_genre'));
				$collections = explode('; ', Input::get('collections'));
				//$developers = explode('; ', Input::get('game_developer'));
				//$publishers = explode('; ', Input::get('game_publisher'));
				$year = Input::get('album_year');

				// general
				$action = Input::get('action', '');
				if('edit' == $action)
				{
					$id = Input::get('element_id');
					$album = Album::find($id);

					DB::table('elements_genres')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('elements_collections')
						->where('element_type', '=', $type)
						->where('element_id', '=', $id)
						->delete();

					DB::table('bands_albums')
						->where('album_id', '=', $id)
						->delete();

					DB::table('tracks')
						->where('album_id', '=', $id)
						->delete();

					/*
					DB::table('publishers_games')
						->where('game_id', '=', $id)
						->delete();
					*/
				}
				else
				{
					$album = new Album();
				}
				$album->name = $name;
				//$game->alt_name = $alt_name;

				/*
				$album->description = EMTypograph::fast_apply($description, array(
					'Text.paragraphs' => 'off',
					'Text.breakline' => 'off',
					'OptAlign.all' => 'off',
					'Nobr.super_nbsp' => 'off'
				));
				*/
				$album->description = $description;
				$album->year = $year;
				$album->verified = 0; // пометка о необходимости модерации
				$album->save();

				// Tracks
				//$description = str_replace("\r", "", $description);
				//$tracks = explode("\n", $description);
				foreach($tracks as $key => $track) {

					if(!empty($track)) {
						$new_track = new Track;
						$new_track->name = $track;
						$new_track->order = $key + 1;
						$new_track->album_id = $album->id;
						$new_track->save();
					}

				}


				// platforms
				if('' != $bands[0]) {
					foreach ($bands as $band) {
						$existing_band = Band::where('name', '=', $band)->first();
						if (isset($existing_band->name)) {
							$existing_band->albums()->save($album);
						} else {
							$new_band = new Band();
							$new_band->name = $band;
							$new_band->save();

							$new_band->albums()->save($album);
						}
					}
				}

				// genres
				if('' != $genres[0]) {
					foreach ($genres as $genre) {
						$element_genre = new ElementGenre();
						$existing_genre = Genre::where('name', '=', $genre)
							->where('element_type', '=', $type)
							->first();
						if (isset($existing_genre->name)) {
							$element_genre->element_type = $type;
							$element_genre->genre_id = $existing_genre->id;
							$element_genre->element_id = $album->id;
							$element_genre->save();
						} else {
							$new_genre = new Genre();
							$new_genre->name = $genre;
							$new_genre->element_type = $type;
							$new_genre->save();

							$element_genre->element_type = $type;
							$element_genre->genre_id = $new_genre->id;
							$element_genre->element_id = $album->id;
							$element_genre->save();
						}
					}
				}

				// collections
				if('' != $collections[0]) {
					//die(print_r($collections));
					foreach ($collections as $collection) {
						$element_collection = new ElementCollection();
						$existing_collection = Collection::where('name', '=', $collection)
							->first();
						if (isset($existing_collection->name)) {
							$element_collection->element_type = $type;
							$element_collection->collection_id = $existing_collection->id;
							$element_collection->element_id = $album->id;
							$element_collection->save();
						} else {
							$new_collection = new Collection();
							$new_collection->name = $collection;
							$new_collection->description = '';

							$new_collection->save();

							$element_collection->element_type = $type;
							$element_collection->collection_id = $new_collection->id;
							$element_collection->element_id = $album->id;
							$element_collection->save();
						}
					}
				}

				// file
				$path = public_path() . '/data/img/covers/' . $section;
				//die($path);
				$fileName = $album->id . '.jpg';
				$full_path = $path.'/'.$fileName;
				//die($full_path);

				if (Input::hasFile('cover')) {

					if (file_exists($full_path)) {
						//$element_cover = $id;
						unlink($full_path);
					} else {
						//$element_cover = $default_cover;
					}

					//Input::file('cover')->move($path, $fileName);
					$this->resize_crop(Input::file('cover')->getRealPath(), $full_path);
				}
				//else{die('no file!');}

				if('edit' != $action) {
					// uploader
					$user = Auth::user();
					$uploader = new Uploader();
					$uploader->element_type = $type;
					$uploader->element_id = $album->id;
					$uploader->user_id = $user->id;
					$uploader->save();
				}

				//print_r($book);
				return Redirect::to('/albums/'.$album->id)->with('message', 'Спасибо, элемент отправлен на модерацию');
			}
		}
	}


	/**
	 * @param $real_path
	 * @param $full_path
	 * @return bool
	 */
	private function resize_crop($real_path, $full_path) {

		$width = 185 * 2;
		$height = 270 * 2;

		$resize = ResizeCrop::resize($real_path, $full_path, $width, 0);
		$size = getimagesize($full_path);
		if($height > $size[1]) {
			$diff = ($height - $size[1]) / 2;
			$crop = ResizeCrop::crop($full_path, $full_path, array(0, -$diff, $width, ($height - $diff)));
		}

		return true;
	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $id
	 * @return bool|\Illuminate\Http\RedirectResponse
	 */
	public function delete(Request $request, $section, $id)	{

		if(RolesHelper::isAdmin($request)) {
			$section_name = SectionsHelper::getSectionType($section);
			$section_name::find($id)->delete();
			return Redirect::to('/'.$section);
		}

		return false;

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @return bool|\Illuminate\Http\RedirectResponse
	 */
	public function q_add(Request $request, $section = '') {
		
		if(RolesHelper::isAdmin($request)) {

			$section_name = SectionsHelper::getSectionType($section);

			$new = new $section_name;

			$name = Input::get('new_name');
			$new->name = urldecode($name);

			$template = Input::get('template');
			if(!empty($template)) {

				switch ($template) {

					case 'marvel_book':

						break;

					case 'dc_book':

						break;

					case 'marvel_film':

						break;

					case 'dc_film':

						break;

					default:

				}

			}

			$new->cover = '';
			$new->description = '';
			$new->year = 0;

			$new->save();
			return Redirect::to('/'.$section.'/'.$new->id);
		}

		return false;

	}
}
