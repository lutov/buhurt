<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use EMTypograph;
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
use App\Models\Data\Meme;
use App\Models\Data\Book;
use App\Models\Data\Film;
use App\Models\Data\Game;
use App\Models\Data\Band;
use App\Models\Data\Album;
use App\Models\Data\Genre;
use App\Models\Data\Track;
use App\Models\Data\Person;
use App\Models\Data\Country;
use App\Models\Data\Company;
use App\Models\User\Uploader;
use App\Models\Data\Platform;
use App\Models\Data\Collection;
use App\Models\Search\ElementGenre;
use App\Models\Search\ElementCollection;

class DatabaseController extends Controller {

	public function add(Request $request, $section = '') {

		switch($section) {
			case 'books':
				$genres = Genre::where('element_type', '=', 'Book')->orderBy('name')->get();
				$countries = array();
				$platforms = array();
				break;

			case 'films':
				$genres = Genre::where('element_type', '=', 'Film')->orderBy('name')->get();
				$countries = Country::orderBy('name')->get();
				$platforms = array();
				break;

			case 'games':
				$genres = Genre::where('element_type', '=', 'Game')->orderBy('name')->get();
				$platforms = Platform::orderBy('name')->get();
				$countries = array();
				break;

			case 'albums':
				$genres = Genre::where('element_type', '=', 'Album')->orderBy('name')->get();
				$platforms = array();
				$countries = array();
				break;

			case 'memes':
				$genres = Genre::where('element_type', '=', 'Meme')->orderBy('name')->get();
				$platforms = array();
				$countries = array();
				break;

			default:
				$section = 'films';
				$genres = Genre::where('element_type', '=', 'Film')->orderBy('name')->get();
				$countries = Country::orderBy('name')->get();
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

		//$section = $request->get('section');
		//$id = $request->get('id');

		switch($section) {

			case 'books':
				$element = Book::find($id);
				$genres = Genre::where('element_type', '=', 'Book')->orderBy('name')->get();
				$countries = array();
				$platforms = array();
				break;

			case 'films':
				$element = Film::find($id); //die(print_r($element));
				$genres = Genre::where('element_type', '=', 'Film')->orderBy('name')->get();
				$countries = Country::orderBy('name')->get();
				$platforms = array();
				break;

			case 'games':
				$element = Game::find($id);
				$genres = Genre::where('element_type', '=', 'Game')->orderBy('name')->get();
				$platforms = Platform::orderBy('name')->get();
				$countries = array();
				break;

			case 'albums':
				$element = Album::find($id);
				$genres = Genre::where('element_type', '=', 'Album')->orderBy('name')->get();
				$platforms = array();
				$countries = array();
				break;

			case 'memes':
				$element = Meme::find($id);
				$genres = Genre::where('element_type', '=', 'Meme')->orderBy('name')->get();
				$platforms = array();
				$countries = array();
				break;

			case 'persons':
				$element = Person::find($id);
				$genres = array();
				$platforms = array();
				$countries = array();
				break;

			case 'companies':
				$element = Company::find($id);
				$genres = array();
				$platforms = array();
				$countries = array();
				break;

			case 'collections':
				$element = Collection::find($id);
				$genres = array();
				$platforms = array();
				$countries = array();
				break;

			case 'bands':
				$element = Band::find($id);
				$genres = array();
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

		return View::make('database.edit.'.$section, array(
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

	/**
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function save(Request $request) {

		//die('<pre>'.print_r($_POST, true));

		$section = $request->get('section');

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
		} elseif('memes' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		} elseif('persons' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		} elseif('companies' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		} elseif('collections' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		} elseif('bands' == $section) {
			$validator = Validator::make(
				$_POST,
				array(
					'name' => array('required', 'min:1'),
					'cover' => array('image', 'max:100')
				)
			);
		} else {
			$validator = new Validator();
		}

		if ($validator->fails()) {

			// Переданные данные не прошли проверку
			// $messages = $validator->messages();
			// foreach ($messages->all() as $message) {}

			return Redirect::back()->withInput()->withErrors($validator);

		} else {

			if('books' == $section) {

				return $this->saveBook($request);

			} elseif('films' == $section) {

				return $this->saveFilm($request);

			} elseif('games' == $section) {

				return $this->saveGame($request);

			} elseif('albums' == $section) {

				return $this->saveAlbum($request);

			} elseif('memes' == $section) {

				return $this->saveMeme($request);

			} elseif('persons' == $section) {

				return $this->savePerson($request);

			} elseif('companies' == $section) {

				return $this->saveCompany($request);

			} elseif('collections' == $section) {

				return $this->saveCollection($request);

			} elseif('bands' == $section) {

				return $this->saveBand($request);

			} else {

				return Redirect::back()->withInput();

			}
		}
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function saveBook(Request $request) {

		$type = 'Book';
		$section = 'books';

		$name = $request->get('book_name');
		$alt_name = $request->get('book_alt_name');
		$description = $request->get('book_description');
		$writers = explode('; ', $request->get('book_writer'));
		$genres = explode('; ', $request->get('book_genre')); //die(print_r($genres));
		$collections = explode('; ', $request->get('collections'));
		$year = $request->get('book_year');
		$publishers = explode('; ', $request->get('book_publisher'));

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {
			$id = $request->get('element_id');
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
		} else {

			$book = new Book();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$book->id = $fill_id;}

		}
		$book->name = $name;
		$book->alt_name = $alt_name;
		$book->description = $this->prepareDescription($description);
		$book->year = $year;

		if(RolesHelper::isAdmin($request)) {
			$book->verified = 1;
		} else {
			$book->verified = 0;
		}

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
					$new_writer->description = '';
					$new_writer->save();

					$new_writer->books()->save($book);
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

		// genres
		if('' != $genres[0]) {
			$this->setGenres($genres, $type, $book->id);
		}

		// collections
		if('' != $collections[0]) {
			$this->setCollections($collections, $type, $book->id);
		}

		// cover
		$this->setCover($request, $section, $book->id);

		// uploader
		if('edit' != $action) {
			$this->setUploader($type, $book->id);
		}

		return $this->returnSuccess($section, $book->id);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function saveFilm(Request $request) {

		$type = 'Film';
		$section = 'films';

		$name = $request->get('film_name');
		$alt_name = $request->get('film_alt_name');
		$description = $request->get('film_description');
		$directors = explode('; ', $request->get('film_director'));
		$producers = explode('; ', $request->get('film_producer'));
		$screenwriters = explode('; ', $request->get('film_screenwriter'));
		$genres = explode('; ', $request->get('film_genre'));
		$collections = explode('; ', $request->get('collections'));
		$countries = explode('; ', $request->get('film_country'));
		$length = $request->get('film_length');
		$year = $request->get('film_year');
		$actors = explode('; ', $request->get('film_actors'));

		$action = $request->get('action', '');

		// general
		if('edit' == $action) {
			$id = $request->get('element_id');
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
		} else {

			$film = new Film();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$film->id = $fill_id;}

		}
		$film->name = $name;
		$film->alt_name = $alt_name;
		$film->description = $this->prepareDescription($description);
		$film->year = $year;
		$film->length = $length;

		if(RolesHelper::isAdmin($request)) {
			$film->verified = 1;
		} else {
			$film->verified = 0;
		}

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
					$new_director->description = '';
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
					$new_screenwriter->description = '';
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
					$new_producer->description = '';
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
					$new_actor->description = '';
					$new_actor->save();

					$new_actor->actions()->save($film);
				}
			}
		}

		// countries
		if('' != $countries[0]) {
			$this->setCountries($countries, $film);
		}

		// genres
		if('' != $genres[0]) {
			$this->setGenres($genres, $type, $film->id);
		}

		// collections
		if('' != $collections[0]) {
			$this->setCollections($collections, $type, $film->id);
		}

		// cover
		$this->setCover($request, $section, $film->id);

		// uploader
		if('edit' != $action) {
			$this->setUploader($type, $film->id);
		}

		//print_r($book);
		return $this->returnSuccess($section, $film->id);

	}

	private function saveGame(Request $request) {

		$type = 'Game';
		$section = 'games';

		$name = $request->get('game_name');
		$alt_name = $request->get('game_alt_name');
		$description = $request->get('game_description', '');
		$platforms = explode('; ', $request->get('game_platform'));
		$genres = explode('; ', $request->get('game_genre'));
		$collections = explode('; ', $request->get('collections'));
		$developers = explode('; ', $request->get('game_developer'));
		$publishers = explode('; ', $request->get('game_publisher'));
		$year = $request->get('game_year');

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {
			$id = $request->get('element_id');
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
		} else {

			$game = new Game();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$game->id = $fill_id;}

		}
		$game->name = $name;
		$game->alt_name = $alt_name;
		$game->description = $this->prepareDescription($description);
		$game->year = $year;

		if(RolesHelper::isAdmin($request)) {
			$game->verified = 1;
		} else {
			$game->verified = 0;
		}

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
			$this->setGenres($genres, $type, $game->id);
		}

		// collections
		if('' != $collections[0]) {
			$this->setCollections($collections, $type, $game->id);
		}

		// cover
		$this->setCover($request, $section, $game->id);

		// uploader
		if('edit' != $action) {
			$this->setUploader($type, $game->id);
		}

		return $this->returnSuccess($section, $game->id);

	}

	private function saveAlbum(Request $request) {

		$type = 'Album';
		$section = 'albums';

		$name = $request->get('album_name');
		//$alt_name = $request->get('game_alt_name');
		$tracks = $request->get('tracks');
		$description = '';//$request->get('album_description', ' ');
		$bands = explode('; ', $request->get('album_band'));
		$genres = explode('; ', $request->get('album_genre'));
		$collections = explode('; ', $request->get('collections'));
		//$developers = explode('; ', $request->get('game_developer'));
		//$publishers = explode('; ', $request->get('game_publisher'));
		$year = $request->get('album_year');

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {
			$id = $request->get('element_id');
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
		} else {

			$album = new Album();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$album->id = $fill_id;}

		}
		$album->name = $name;
		//$album->alt_name = $alt_name;
		$album->description = $this->prepareDescription($description);
		$album->year = $year;

		if(RolesHelper::isAdmin($request)) {
			$album->verified = 1;
		} else {
			$album->verified = 0;
		}

		$album->save();

		// Tracks
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
			$this->setGenres($genres, $type, $album->id);
		}

		// collections
		if('' != $collections[0]) {
			$this->setCollections($collections, $type, $album->id);
		}

		$this->setCover($request, $section, $album->id);

		if('edit' != $action) {
			// uploader
			$this->setUploader($type, $album->id);
		}

		//print_r($book);
		return $this->returnSuccess($section, $album->id);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function savePerson(Request $request) {

		$type = 'Person';
		$section = 'persons';

		$name = $request->get('name', '');
		$description = $request->get('description', ' ');

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {
			$id = $request->get('element_id');
			$element = $type::find($id);
		} else {

			$element = new $type();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$element->id = $fill_id;}

		}
		$element->name = $name;
		$element->description = $this->prepareDescription($description);
		$element->save();

		$this->setCover($request, $section, $element->id);

		if('edit' != $action) {
			// uploader
			$this->setUploader($type, $element->id);
		}

		//print_r($book);
		return $this->returnSuccess($section, $element->id);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function saveMeme(Request $request) {

		$type = 'Meme';
		$section = 'memes';

		$name = $request->get('name', '');
		$alt_name = $request->get('alt_name', '');
		$description = $request->get('description', ' ');
		$genres = explode('; ', $request->get('genre'));
		$collections = explode('; ', $request->get('collections'));
		$year = $request->get('year', 0);

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {

			$id = $request->get('element_id');
			$element = $type::find($id);

			DB::table('elements_genres')
				->where('element_type', '=', $type)
				->where('element_id', '=', $id)
				->delete()
			;

		} else {

			$element = new $type();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$element->id = $fill_id;}

		}
		$element->name = $name;
		$element->alt_name = $alt_name;
		$element->description = $this->prepareDescription($description);
		$element->year = $year;

		if(RolesHelper::isAdmin($request)) {
			$element->verified = 1;
		} else {
			$element->verified = 0;
		}

		$element->save();

		// genres
		if('' != $genres[0]) {
			$this->setGenres($genres, $type, $element->id);
		}

		// collections
		if('' != $collections[0]) {
			$this->setCollections($collections, $type, $element->id);
		}

		$this->setCover($request, $section, $element->id);

		if('edit' != $action) {
			// uploader
			$this->setUploader($type, $element->id);
		}

		//print_r($book);
		return $this->returnSuccess($section, $element->id);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function saveCompany(Request $request) {

		$type = 'Company';
		$section = 'companies';

		$name = $request->get('name', '');
		$description = $request->get('description', ' ');

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {
			$id = $request->get('element_id');
			$element = $type::find($id);
		} else {

			$element = new $type();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$element->id = $fill_id;}

		}
		$element->name = $name;
		$element->description = $this->prepareDescription($description);
		$element->save();

		$this->setCover($request, $section, $element->id);

		if('edit' != $action) {
			// uploader
			$this->setUploader($type, $element->id);
		}

		//print_r($book);
		return $this->returnSuccess($section, $element->id);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function saveCollection(Request $request) {

		$type = 'Collection';
		$section = 'collections';

		$name = $request->get('name', '');
		$description = $request->get('description', ' ');

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {
			$id = $request->get('element_id');
			$element = $type::find($id);
		} else {

			$element = new $type();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$element->id = $fill_id;}

		}
		$element->name = $name;
		$element->description = $this->prepareDescription($description);
		$element->save();

		$this->setCover($request, $section, $element->id);

		if('edit' != $action) {
			// uploader
			$this->setUploader($type, $element->id);
		}

		//print_r($book);
		return $this->returnSuccess($section, $element->id);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function saveBand(Request $request) {

		$type = 'Band';
		$section = 'bands';

		$name = $request->get('name', '');
		$description = $request->get('description', ' ');

		// general
		$action = $request->get('action', '');
		if('edit' == $action) {
			$id = $request->get('element_id');
			$element = $type::find($id);
		} else {

			$element = new $type();
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$element->id = $fill_id;}

		}
		$element->name = $name;
		$element->description = $this->prepareDescription($description);
		$element->save();

		$this->setCover($request, $section, $element->id);

		if('edit' != $action) {
			// uploader
			$this->setUploader($type, $element->id);
		}

		//print_r($book);
		return $this->returnSuccess($section, $element->id);

	}

	/**
	 * @param $description
	 * @return string
	 */
	private function prepareDescription($description) {

		//die(print_r($description));

		if(!empty($description)) {

			return EMTypograph::fast_apply($description, array(
				'Text.paragraphs' => 'off',
				'Text.breakline' => 'off',
				'OptAlign.all' => 'off',
				'Nobr.super_nbsp' => 'off'
			));

		} else {

			return ' ';

		}

	}

	/**
	 * @param array $genres
	 * @param string $type
	 * @param int $element_id
	 */
	private function setGenres(array $genres = array(), string $type = '', int $element_id = 0) {

		foreach ($genres as $genre) {

			$element_genre = new ElementGenre();
			$existing_genre = Genre::where('name', '=', $genre)
				->where('element_type', '=', $type)
				->first();

			if (isset($existing_genre->name)) {

				$element_genre->element_type = $type;
				$element_genre->genre_id = $existing_genre->id;
				$element_genre->element_id = $element_id;
				$element_genre->save();

			} else {

				$new_genre = new Genre();
				$new_genre->name = $genre;
				$new_genre->element_type = $type;
				$new_genre->save();

				$element_genre->element_type = $type;
				$element_genre->genre_id = $new_genre->id;
				$element_genre->element_id = $element_id;
				$element_genre->save();

			}

		}

	}

	/**
	 * @param array $collections
	 * @param string $type
	 * @param int $element_id
	 */
	private function setCollections(array $collections = array(), string $type = '', int $element_id = 0) {

		//die(print_r($collections));
		foreach ($collections as $collection) {

			$element_collection = new ElementCollection();
			$existing_collection = Collection::where('name', '=', $collection)->first();
			if (isset($existing_collection->name)) {

				$element_collection->element_type = $type;
				$element_collection->collection_id = $existing_collection->id;
				$element_collection->element_id = $element_id;
				$element_collection->save();

			} else {

				$new_collection = new Collection();
				$new_collection->name = $collection;
				$new_collection->description = '';

				$new_collection->save();

				$element_collection->element_type = $type;
				$element_collection->collection_id = $new_collection->id;
				$element_collection->element_id = $element_id;
				$element_collection->save();

			}

		}

	}

	/**
	 * @param Request $request
	 * @param string $section
	 * @param int $element_id
	 */
	private function setCover(Request $request, string $section = '', int $element_id) {

		// file
		$path = public_path().'/data/img/covers/'.$section;
		//die($path);
		$fileName = $element_id.'.jpg';
		$full_path = $path.'/'.$fileName;
		//die($full_path);

		if ($request->hasFile('cover')) {

			if (file_exists($full_path)) {
				//$element_cover = $id;
				unlink($full_path);
			} //else {
				//$element_cover = $default_cover;
			//}

			//$request->file('cover')->move($path, $fileName);
			$this->resizeCrop($request->file('cover')->getRealPath(), $full_path);
		}
		//else{die('no file!');}

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
	 * @param array $countries
	 * @param Film $film
	 */
	private function setCountries(array $countries = array(), Film $film) {

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

	/**
	 * @param string $section
	 * @param int $element_id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	private function returnSuccess(string $section = '', int $element_id = 0) {

		return Redirect::to('/'.$section.'/'.$element_id)->with('message', 'Спасибо, элемент отправлен на модерацию');

	}

	/**
	 * @param $real_path
	 * @param $full_path
	 * @return bool
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

		return true;
	}

	/**
	 * @param Request $request
	 * @param $section
	 * @param $id
	 * @return bool
	 * @throws \Exception
	 */
	public function delete(Request $request, $section, $id)	{

		if(RolesHelper::isAdmin($request)) {

			//die(__DIR__);

			$section_name = SectionsHelper::getSectionType($section);

			ElementsHelper::deleteElement($id, $section, $section_name);

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

		$name = $request->get('new_name', '');
		
		if(!empty($name)) {

			$type = SectionsHelper::getSectionType($section);

			$new = new $type;
			$fill_id = $this->getMissingId($section);
			if($fill_id) {$new->id = $fill_id;}

			$new->name = TextHelper::getCleanName(urldecode($name));

			$new->cover = '';
			$new->description = '';
			$new->year = 0;

			if(RolesHelper::isAdmin($request)) {
				$new->verified = 0;//1;
			} else {
				$new->verified = 0;
			}

			$new->save();

			$element_id = $new->id;

			$template = $request->get('template');
			if(!empty($template)) {

				switch ($template) {

					case 'fiction_book':
						$genres = array('Фантастика и фэнтези');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'action_book':
						$genres = array('Детективы и боевики');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'adventure_book':
						$genres = array('Приключения и исторический роман');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'lovestory_book':
						$genres = array('Любовный роман');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'novel_book':
						$genres = array('Современная проза');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'nonfiction_book':
						$genres = array('Публицистика и нон-фикшн');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'poetry_book':
						$genres = array('Поэзия');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'comic_book':
						$genres = array('Комиксы и манга');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'marvel_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('Marvel Comics');
						$this->setGenres($genres, $type, $element_id);
						$this->setCollections($collections, $type, $element_id);
						break;

					case 'dc_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('DC Comics');
						$this->setGenres($genres, $type, $element_id);
						$this->setCollections($collections, $type, $element_id);
						break;

					case 'image_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('Image Comics');
						$this->setGenres($genres, $type, $element_id);
						$this->setCollections($collections, $type, $element_id);
						break;

					case 'valiant_book':
						$genres = array('Комиксы и манга', 'Фантастика и фэнтези');
						$collections = array('Valiant Comics');
						$this->setGenres($genres, $type, $element_id);
						$this->setCollections($collections, $type, $element_id);
						break;

					case 'fiction_film':
						$genres = array('Фантастика');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'marvel_film':
						$genres = array('Фантастика');
						$collections = array('Marvel Comics');
						$this->setGenres($genres, $type, $element_id);
						$this->setCollections($collections, $type, $element_id);
						break;

					case 'dc_film':
						$genres = array('Фантастика');
						$collections = array('DC Comics');
						$this->setGenres($genres, $type, $element_id);
						$this->setCollections($collections, $type, $element_id);
						break;

					case 'fantasy_film':
						$genres = array('Фэнтези');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'drama_film':
						$genres = array('Драмы');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'melodrama_film':
						$genres = array('Мелодрамы');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'comedy_film':
						$genres = array('Комедии');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'family_film':
						$genres = array('Семейные');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'adventure_film':
						$genres = array('Приключения');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'detective_film':
						$genres = array('Детективы');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'action_film':
						$genres = array('Экшены');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'thriller_film':
						$genres = array('Триллеры');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'horror_film':
						$genres = array('Ужасы');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'series_film':
						$genres = array('Сериалы');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'animated_film':
						$genres = array('Мультфильмы');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'anime':
						$genres = array('Аниме', 'Мультфильмы');
						$countries = array('Япония');
						//$collections = array('Marvel Comics');
						$this->setGenres($genres, $type, $element_id);
						$this->setCountries($countries, $new);
						//$this->setCollections($collections, $type, $element_id);
						break;

					case 'action_game':
						$genres = array('Экшен');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'roleplay_game':
						$genres = array('Ролевые игры');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'strategy_game':
						$genres = array('Стратегии');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'quest_game':
						$genres = array('Приключения');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'arcade_game':
						$genres = array('Аркады');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'rock_album':
						$genres = array('Rock');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'rap_album':
						$genres = array('Rap');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'pop_album':
						$genres = array('Pop');
						$this->setGenres($genres, $type, $element_id);
						break;

					case 'electronic_album':
						$genres = array('Electronic');
						$this->setGenres($genres, $type, $element_id);
						break;

					default:

				}

			}

			$this->setUploader($type, $element_id);

			$user_id = Auth::user()->id;
			$event = new Event();
			$event->event_type = 'UserAdd';
			$event->element_type = $type;
			$event->element_id = $element_id;
			$event->user_id = $user_id;
			$event->name = $name;
			$event->text = 'Добавлено произведение'; //«'.$search.'»';
			$event->save();

			return Redirect::to('/'.$section.'/'.$new->id);
		}

		return false;

	}

	/**
	 * @param string $section
	 * @return int
	 */
	private function getMissingId(string $section = '') {

		$missing_id = 0;

		$query = "SELECT (`".$section."`.`id`+1) as `empty_id`
		FROM `".$section."`
		WHERE (
			SELECT 1 FROM `".$section."` as `st` WHERE `st`.`id` = (`".$section."`.`id` + 1)
		) IS NULL
		ORDER BY `".$section."`.`id`
		LIMIT 1";

		$result = DB::select($query)[0]->empty_id;

		//echo DebugHelper::dump($result, 1); die();

		if(!empty($result)) {$missing_id = $result;}

		return $missing_id;

	}

}
