<?php namespace App\Http\Controllers;

use App\Models\Helpers\DebugHelper;
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

			set_time_limit(0);

			echo ini_get('max_execution_time'); die();

			//echo $book->name;
			//echo 'yes';

			//$genres = Helpers::get_fav_genres(1, 'Film');
			//echo Helpers::array2string($genres, ', ', '/genres/films/');

			$basic_path = env('LOG_PATH');

			$basic_url = 'https://api.thegamesdb.net/';

			$public_key = 'afd7d4a705ec6732dfd5a6643040ad6bf1d28743a82f5673be0078ee4c5e2f58';
			$private_key = '8018b431dd746d697ee678b38caf23ff5765978245f9c4992ba708423f07ef45';

			$platforms_url = 'Platforms';

			$params = array(
				'apikey' => $public_key,
			);

			//$platforms = DebugHelper::makeRequest($basic_url.$platforms_url, $params);

			//$debug_string = DebugHelper::dump($platforms, true);

			//$debug_array = serialize($platforms);

			//DebugHelper::dumpToFile($debug_array, 'platforms');

			//echo $debug_string;

			//$platforms_file = 'platforms 2018-09-30 16-01-31.log';

			//$platforms = unserialize(file_get_contents($basic_path.'/'.$platforms_file));

			//$debug_string = DebugHelper::dump($platforms->data->platforms, true);
			//echo $debug_string;

			/*
			$top_platforms = array();

			$top_platforms[] = (array) $platforms->data->platforms->{'4916'};
			$top_platforms[] = (array) $platforms->data->platforms->{'28'};
			$top_platforms[] = (array) $platforms->data->platforms->{'37'};
			$top_platforms[] = (array) $platforms->data->platforms->{'14'};
			$top_platforms[] = (array) $platforms->data->platforms->{'15'};
			$top_platforms[] = (array) $platforms->data->platforms->{'4920'};
			$top_platforms[] = (array) $platforms->data->platforms->{'24'};
			$top_platforms[] = (array) $platforms->data->platforms->{'4912'};
			$top_platforms[] = (array) $platforms->data->platforms->{'3'};
			$top_platforms[] = (array) $platforms->data->platforms->{'8'};
			$top_platforms[] = (array) $platforms->data->platforms->{'7'};
			$top_platforms[] = (array) $platforms->data->platforms->{'4'};
			$top_platforms[] = (array) $platforms->data->platforms->{'5'};
			$top_platforms[] = (array) $platforms->data->platforms->{'2'};
			$top_platforms[] = (array) $platforms->data->platforms->{'4971'};
			$top_platforms[] = (array) $platforms->data->platforms->{'9'};
			$top_platforms[] = (array) $platforms->data->platforms->{'38'};
			$top_platforms[] = (array) $platforms->data->platforms->{'1'};
			$top_platforms[] = (array) $platforms->data->platforms->{'16'};
			$top_platforms[] = (array) $platforms->data->platforms->{'18'};
			$top_platforms[] = (array) $platforms->data->platforms->{'36'};
			$top_platforms[] = (array) $platforms->data->platforms->{'17'};
			$top_platforms[] = (array) $platforms->data->platforms->{'10'};
			$top_platforms[] = (array) $platforms->data->platforms->{'11'};
			$top_platforms[] = (array) $platforms->data->platforms->{'12'};
			$top_platforms[] = (array) $platforms->data->platforms->{'4919'};
			$top_platforms[] = (array) $platforms->data->platforms->{'13'};
			$top_platforms[] = (array) $platforms->data->platforms->{'39'};
			$top_platforms[] = (array) $platforms->data->platforms->{'6'};

			$debug_string = DebugHelper::dump($top_platforms, true);
			echo $debug_string;

			$debug_array = serialize($top_platforms);

			DebugHelper::dumpToFile($debug_array, 'top_platforms');
			*/

			//$platforms_file = 'top_platforms 2018-09-30 16-25-04.log';

			//$platforms = unserialize(file_get_contents($basic_path.'/'.$platforms_file));

			//$debug_string = DebugHelper::dump($platforms, true);
			//echo $debug_string;

			$platforms = array(
				array('id' => 1, 'alias' => 'pc'),
			);

			$games_by_platforms_url = 'Games/ByPlatformID';

			$games = array();

			foreach($platforms as $platform) {

				$games = array();

				$params['id'] = $platform['id'];

				//$platform_games = DebugHelper::makeRequest($basic_url.$games_by_platforms_url, $params);

				//$games[$platform['alias']] = $platform_games;

				$games = $this->getPage($basic_url.$games_by_platforms_url, $platform, $params, $games);

				$debug_array = serialize($games);

				$games_platforms_file = DebugHelper::dumpToFile($debug_array, 'games_by_platforms');

			}

			//$debug_array = serialize($games);

			//$games_platforms_file = DebugHelper::dumpToFile($debug_array, 'games_by_platforms');

			//echo count($games);

			//$games_platforms = unserialize(file_get_contents($games_platforms_file));

			//$debug_string = DebugHelper::dump($games_platforms, true);
			//echo $debug_string;

		} else {

			echo 'no';

		}
    }

	/**
	 * @param string $url
	 * @param array $platform
	 * @param array $params
	 * @param array $games
	 * @return array
	 */
    private function getPage(string $url, array $platform, array $params, array $games) {

		$platform_games = DebugHelper::makeRequest($url, $params);

		$games[$platform['alias']][] = $platform_games;

		if(!empty($platform_games->pages->next)) {$games = $this->getPage($platform_games->pages->next, $platform, $params, $games);}

		return $games;

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