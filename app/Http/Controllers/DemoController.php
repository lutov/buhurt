<?php namespace App\Http\Controllers;

use App\Models\Helpers\DebugHelper;
use App\Models\Helpers\RolesHelper;
use Auth;
use DB;
use Illuminate\Http\Request;
use mysqli;
use PDO;
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

	private $basic_path;

	private $basic_url = 'https://api.thegamesdb.net/';

	private $public_key = 'afd7d4a705ec6732dfd5a6643040ad6bf1d28743a82f5673be0078ee4c5e2f58';
	private $private_key = '8018b431dd746d697ee678b38caf23ff5765978245f9c4992ba708423f07ef45';

	private $params = array();

	private $platforms_url = 'Platforms';

	private $games_by_platforms_url = 'Games/ByPlatformID';

	private $platforms;

	private $games = array();

	private $next_urls = array();

	private $next_url = '';

	private $next_file = 'next 2018-10-07 12-54-53.log';

	public function __construct() {

		set_time_limit(0);

		//echo ini_get('max_execution_time'); die();

		$this->basic_path = env('LOG_PATH');

		$this->params = array(
			'apikey' => $this->public_key,
		);

		$this->platforms = new \stdClass();

		$this->next_urls = unserialize(file_get_contents($this->basic_path.'/'.$this->next_file));

		$this->next_url = $this->basic_url.$this->games_by_platforms_url;

	}

	/**
	 * @param Request $request
	 */
	public function index(Request $request) {

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

		if(RolesHelper::isAdmin($request)) {

			$debug_string = '';

			//echo $book->name;
			//echo 'yes';

			//$genres = Helpers::get_fav_genres(1, 'Film');
			//echo Helpers::array2string($genres, ', ', '/genres/films/');

			/*
			$aContext = array(
				'http' => array(
					//'proxy' => 'tcp://195.123.217.74:889',
					//'proxy' => 'tcp://jack-sparrow.redirectme.net:1080',
					//'proxy' => 'tcp://95.216.55.138:6968',
					//'proxy' => 'tcp://5.167.51.235:8080',
					//'proxy' => 'tcp://119.2.88.61:4145',
					//'proxy' => 'tcp://198.50.142.47:3128',
					//'proxy' => 'tcp://37.61.224.231:8195',
					'proxy' => 'tcp://51.38.234.95:8080',
					'request_fulluri' => true,
				),
			);
			$cxContext = stream_context_create($aContext);
			*/

			//echo $this->getPageByPlatforms(); // $cxContext

			/*
			$a = 1;
			$z = 27;

			for ($i = $a; $i <= $z; $i++) {

				$games_file = 'games ('.$i.').log';

				$games = unserialize(file_get_contents($this->basic_path . '/' . $games_file));

				//$debug_string .= DebugHelper::dump($games, 1);

				//echo $debug_string;

				foreach($games as $platform_name => $platform) {

					foreach($platform[0]->data->games as $game) {

						$game_title = addslashes($game->game_title);
						if(!empty($game->release_date)) {$release_date = $game->release_date;} else {$release_date = date('Y-m-d');}

						$query = "INSERT INTO `_gamesdb` (game_id, game_title, release_date, platform, platform_name, developers) VALUES (".$game->id.", '".$game_title."', '".$release_date."', ".$game->platform.", '".$platform_name."', '".serialize($game->developers)."')";

						//echo $query."\n\n";

						DB::insert($query);

					}

				}

			}
			*/

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

		//echo DebugHelper::dump($params, 1);

    	//echo $url."\n\n"; die();

		//$platform_games = DebugHelper::makeRequest($url, $params, 1);
		$platform_games = DebugHelper::getResult($url, 0); // , $params['proxy']

		$games[$platform['alias']][] = $platform_games;

		if(!empty($platform_games->pages->next)) {
			//$games = $this->getPage($platform_games->pages->next, $platform, $params, $games);
			$this->next_urls[$platform['id']] = $platform_games->pages->next;
			//echo $platform_games->pages->next."\n\n";
		} else {
			//die('no future');
		}

		return $games;

	}

	/**
	 * @return string
	 */
	private function getPlatformsList() {

		$platforms = DebugHelper::makeRequest($this->basic_url.$this->platforms_url, $this->params);

		$debug_string = DebugHelper::dump($platforms, true);

		$debug_array = serialize($platforms);

		$platforms_file = DebugHelper::dumpToFile($debug_array, 'platforms');

		//$platforms_file = 'platforms 2018-09-30 16-01-31.log';

		$this->platforms = unserialize(file_get_contents($this->basic_path.'/'.$platforms_file))->data->platforms;

		$debug_string .= DebugHelper::dump($this->platforms, true);

		return $debug_string;

	}

	/**
	 * @return string
	 */
	private function getTopPlatformsList() {

		$debug_string = '';

		/*
		$top_platforms = array();

		$platforms = $this->platforms;

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

		$debug_string .= DebugHelper::dump($top_platforms, true);

		$debug_array = serialize($top_platforms);

		$platforms_file = DebugHelper::dumpToFile($debug_array, 'top_platforms');
		*/

		$platforms_file = 'top_platforms 2018-09-30 16-25-04.log';

		$this->platforms = unserialize(file_get_contents($this->basic_path.'/'.$platforms_file));

		$debug_string .= DebugHelper::dump($this->platforms, true);

		return $debug_string;

	}

	/**
	 * @return string
	 */
	private function getPageByPlatforms() { // $proxy

		$debug_string = '';

		$this->getTopPlatformsList();

		$games = $this->games;

		$params = $this->params;
		//$params['proxy'] = $proxy;

		foreach($this->platforms as $platform) {

			//$games = array();

			$params['id'] = $platform['id'];

			//$platform_games = DebugHelper::makeRequest($basic_url.$games_by_platforms_url, $params);

			//$games[$platform['alias']] = $platform_games;

			if(isset($this->next_urls[$platform['id']])) {

				$next_url = $this->next_urls[$platform['id']];

			} else {

				$next_url = $this->next_url;
			}

			//echo $next_url."\n\n";

			$games = $this->getPage($next_url, $platform, $params, $games);

			//$debug_array = serialize($games);

			//$games_platforms_file = DebugHelper::dumpToFile($debug_array, 'games_by_platforms');

			echo DebugHelper::dump($games, 1); //die();

		}

		$debug_array = serialize($games);

		$games_platforms_file = DebugHelper::dumpToFile($debug_array, 'games_by_platforms');

		$debug_string .= "\n\n".count($games)."\n\n";

		//$games_platforms = unserialize(file_get_contents($games_platforms_file));

		//$debug_string = DebugHelper::dump($games_platforms, true);
		//echo $debug_string;

		if(count($this->next_urls)) {

			$debug_array = serialize($this->next_urls);
			$next_file = DebugHelper::dumpToFile($debug_array, 'next');

			$debug_string .= "\n\n".$next_file."\n\n";

			$debug_string .= DebugHelper::dump($this->next_urls, true);

		}

		return $debug_string;

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