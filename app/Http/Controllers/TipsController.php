<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 08.03.2019
 * Time: 16:16
 */

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Band;
use App\Models\Book;
use App\Models\Collection;
use App\Models\Company;
use App\Models\Country;
use App\Models\Film;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Platform;
use Illuminate\Support\Facades\Input;

class TipsController extends Controller {

	protected $x_small_limit = 3;
	protected $small_limit = 5;
	protected $normal_limit = 28;
	protected $default_sort = 'name';
	protected $default_sort_direction = 'asc';

	/**
	 * @param $model
	 * @return string
	 */
	private function getName($model) {

		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

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

	private function getGenre(string $type) {

		$limit = $this->small_limit;

		$query = urldecode(Input::get('term'));

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
	public function person_name() {
		$model = new Person();
		$this->getName($model);
	}

	public function company_name() {
		$model = new Company();
		$this->getName($model);
	}

	public function collection_name() {
		$model = new Collection();
		$this->getName($model);
	}

	public function platform_name() {
		$model = new Platform();
		$this->getName($model);
	}

	public function country_name() {
		$model = new Country();
		$this->getName($model);
	}

	public function book_name() {
		$model = new Book();
		$this->getName($model);
	}

	public function film_name() {
		$model = new Film();
		$this->getName($model);
	}

	public function game_name() {
		$model = new Game();
		$this->getName($model);
	}

	public function album_name() {
		$model = new Album();
		$this->getName($model);
	}

	public function band_name() {
		$model = new Band();
		$this->getName($model);
	}
	/* NAMES */

	/* GENRES */
	public function book_genre() {
		$type = 'Book';
		$this->getGenre($type);
	}

	public function film_genre() {
		$type = 'Film';
		$this->getGenre($type);
	}

	public function game_genre() {
		$type = 'Game';
		$this->getGenre($type);
	}

	public function album_genre() {
		$type = 'Album';
		$this->getGenre($type);
	}
	/* GENRES */

}