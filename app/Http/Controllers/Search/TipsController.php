<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 08.03.2019
 * Time: 16:16
 */

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Album;
use App\Models\Data\Band;
use App\Models\Data\Book;
use App\Models\Data\Collection;
use App\Models\Data\Company;
use App\Models\Data\Country;
use App\Models\Data\Film;
use App\Models\Data\Game;
use App\Models\Data\Genre;
use App\Models\Data\Meme;
use App\Models\Data\Person;
use App\Models\Data\Platform;

class TipsController extends Controller {

	protected $x_small_limit = 3;
	protected $small_limit = 5;
	protected $normal_limit = 28;
	protected $default_sort = 'name';
	protected $default_sort_direction = 'asc';

	/**
	 * @param Request $request
	 * @param $model
	 */
	private function getName(Request $request, $model) {

		$limit = $this->small_limit;

		$query = urldecode($request->get('term'));

		$result = '';

		if(!empty($query)) {

			$result = $model->where('name', 'like', '%'.$query.'%')
				->limit($limit)
				->pluck('name')
			;

		}

		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($result);
		die();

	}

	/**
	 * @param Request $request
	 * @param string $type
	 */
	private function getGenre(Request $request, string $type) {

		$limit = $this->small_limit;

		$query = urldecode($request->get('term'));

		$result = '';

		if(!empty($query)) {
			$result = Genre::where('name', 'like', '%'.$query.'%')
				->where('element_type', '=', $type)
				->limit($limit)
				->pluck('name')
			;
		}

		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($result);
		die();

	}

	/* NAMES */
	public function person_name(Request $request) {
		$model = new Person();
		$this->getName($request, $model);
	}

	public function company_name(Request $request) {
		$model = new Company();
		$this->getName($request, $model);
	}

	public function collection_name(Request $request) {
		$model = new Collection();
		$this->getName($request, $model);
	}

	public function platform_name(Request $request) {
		$model = new Platform();
		$this->getName($request, $model);
	}

	public function country_name(Request $request) {
		$model = new Country();
		$this->getName($request, $model);
	}

	public function book_name(Request $request) {
		$model = new Book();
		$this->getName($request, $model);
	}

	public function film_name(Request $request) {
		$model = new Film();
		$this->getName($request, $model);
	}

	public function game_name(Request $request) {
		$model = new Game();
		$this->getName($request, $model);
	}

	public function album_name(Request $request) {
		$model = new Album();
		$this->getName($request, $model);
	}

	public function meme_name(Request $request) {
		$model = new Meme();
		$this->getName($request, $model);
	}

	public function band_name(Request $request) {
		$model = new Band();
		$this->getName($request, $model);
	}
	/* NAMES */

	/* GENRES */
	public function book_genre(Request $request) {
		$type = 'Book';
		$this->getGenre($request, $type);
	}

	public function film_genre(Request $request) {
		$type = 'Film';
		$this->getGenre($request, $type);
	}

	public function game_genre(Request $request) {
		$type = 'Game';
		$this->getGenre($request, $type);
	}

	public function album_genre(Request $request) {
		$type = 'Album';
		$this->getGenre($request, $type);
	}

	public function meme_genre(Request $request) {
		$type = 'Meme';
		$this->getGenre($request, $type);
	}
	/* GENRES */

}