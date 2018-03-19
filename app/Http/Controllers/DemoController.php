<?php namespace App\Http\Controllers;

use Auth;
use DB;
use View;
use Input;
use Redirect;
use App\Models\Rate;
use App\Models\Film;
use App\Models\Book;
use App\Models\Genre;
use App\Models\ElementGenre;
use App\Models\Helpers;
use App\Models\Section;
use App\Models\Wanted;

class DemoController extends Controller {

	/**
	 *
	 */
	public function index() {

		/*
		$query = "select
			element_type, element_id, count(rate) as `rates`, sum(rate) as `sum`, sum(rate)/count(rate) as `rating`
			from rates
			where element_type='Book'
			group by element_id
			order by rates desc
			limit 10
		";
		*/

		if(Helpers::is_admin()) {

			//echo $book->name;
			echo 'yes';

			$genres = Helpers::get_fav_genres(1, 'Film');
			echo Helpers::array2string($genres, ', ', '/genres/films/');

		} else {

			echo 'no';

		}
    }


	public function moderator() {

		echo 'moderator';

		if(Helpers::is_admin()) {

			//echo $book->name;

		} else {

			echo 'no';

		}
	}
}