<?php namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class RandomController extends Controller {

	/**
	 * @return mixed
	 */
	public function index() {

		return View::make('random.index', array(

		));

	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function books(Request $request) {

		$bookname_table = 'random_booknames';

		$word_one = 'ХЛЕБ';
		$word_two = 'БЛАСТЕР';

		$rows = DB::table($bookname_table)
			//->remember(60)
			->count()
		;
		//die(print_r($rows));

		$rand_row = rand(0, $rows);
		//die(print_r($rand_row));

		$word_one = DB::table($bookname_table)
			//->limit($rand_row, 1)
			->take(1)
			->skip($rand_row)
			->value('name')
			//->toSql()
		;
		//die($word_one);

		$rand_row = rand(0, $rows);

		$word_two = DB::table($bookname_table)
			//->limit($rand_row, 1)
			->take(1)
			->skip($rand_row)
			->value('name')
		;

		$word_one = mb_strtoupper($word_one, 'UTF-8');
		$word_two = mb_strtoupper($word_two, 'UTF-8');

		// Writers
		$name_table = 'random_names';

		$rows1 = DB::table($name_table)
			->where('order', '=', 1)
			//->remember(60)
			->count()
		;
		$rows2 = DB::table($name_table)
			->where('order', '=', 2)
			//->remember(60)
			->count()
		;
		//die(print_r($rows));

		$rand_row = rand(0, $rows1);
		//die(print_r($rand_row));

		$writer_word_one = DB::table($name_table)
			//->limit($rand_row, 1)
			->where('order', '=', 1)
			->take(1)
			->skip($rand_row)
			->value('name')
			//->toSql()
		;
		//die($word_one);

		$rand_row = rand(0, $rows2);

		$writer_word_two = DB::table($name_table)
			//->limit($rand_row, 1)
			->where('order', '=', 2)
			->take(1)
			->skip($rand_row)
			->value('name')
		;

		$writer_word_one = mb_strtoupper($writer_word_one, 'UTF-8');
		$writer_word_two = mb_strtoupper($writer_word_two, 'UTF-8');

		// Publishers
		$publisher_table = 'companies';
		$rows = DB::table($publisher_table)
			//->remember(60)
			->count()
		;
		$rand_row = rand(0, $rows);
		$publisher = DB::table($publisher_table)
			->take(1)
			->skip($rand_row)
			->value('name')
		;

		// Genres
		$genre_table = 'genres';
		$rows = DB::table($genre_table)
			->where('element_type', '=', 'Book')
			//->remember(60)
			->count()
		;
		$rand_row = rand(0, $rows);
		$genre = DB::table($genre_table)
			->where('element_type', '=', 'Book')
			->take(1)
			->skip($rand_row)
			->value('name')
		;

		// first we include phpmorphy library
		require_once(public_path() . '/data/phpmorphy/src/common.php');

		// set some options
		$opts = array(
			// storage type, follow types supported
			'storage' => PHPMORPHY_STORAGE_FILE,
			// Extend graminfo for getAllFormsWithGramInfo method call
			'with_gramtab' => false,
			// Enable prediction by suffix
			'predict_by_suffix' => true,
			// Enable prediction by prefix
			'predict_by_db' => true
		);

		// Path to directory where dictionaries located
		$dir = public_path() . '/data/phpmorphy/dicts';

		// Create descriptor for dictionary located in $dir directory with russian language
		$dict_bundle = new \phpMorphy_FilesBundle($dir, 'rus');

		// Create phpMorphy instance
		try {
			$morphy = new \phpMorphy($dict_bundle, $opts);
		} catch(\phpMorphy_Exception $e) {
			die('Error occured while creating phpMorphy instance: ' . $e->getMessage());
		}

		try {

			$base_form = $morphy->getAllForms($word_one);
			$all_forms = $morphy->getAllForms($word_two);

			//$writer_base_form = $morphy->getAllForms($writer_word_one);
			//$writer_all_forms = $morphy->getAllForms($writer_word_two);

		} catch(\phpMorphy_Exception $e) {
			die('Error occured while text processing: ' . $e->getMessage());
		}

		$book = TextHelper::mb_ucwords(mb_strtolower($base_form[0])).' '.mb_strtolower($all_forms[1]);
		$writers = TextHelper::mb_ucwords($writer_word_one.' '.$writer_word_two);
		$year = '1'.rand(0, 9).rand(0, 9).rand(0, 9 );
		$genre = TextHelper::mb_ucfirst($genre);
		$publisher = TextHelper::mb_ucwords($publisher);
		$comments = '';
		$user_rate = 0;
		$wanted = 0;
		$not_wanted = 0;
		$cover = 0;
		$section = 'books';

		return View::make('random.books', array(
			'request' => $request,
			'book' => $book,
			'writers' => $writers,
			'year' => $year,
			'genre' => $genre,
			'publisher' => $publisher,
			'cover' => $cover,
			'rate' => $user_rate,
			'wanted' => $wanted,
			'not_wanted' => $not_wanted,
			'comments' => $comments,
			'section' => $section
		));
	}

	/**
	 * @param $section
	 * @return mixed
	 */
	public function get_json($section) {

		$type = SectionsHelper::getSectionType($section);
		$list = $type::pluck('id');
		$size = count($list);
		$random_id = $list[rand(0, $size)];

		$path = '/api/'.$section.'/'.$random_id;

		return Redirect::to($path);

	}

}
