<?php namespace App\Http\Controllers\Search;

use App\Helpers\RolesHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\Data\Album;
use App\Models\Data\Band;
use App\Models\Data\Book;
use App\Models\Data\Film;
use App\Models\Data\Game;
use App\Models\Data\NotFound;
use App\Models\Data\Person;
use App\Models\User\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use YaMetrika;

class SearchController extends Controller
{

    protected string $section = 'search';

    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function everything(Request $request)
    {
        $raw = $request->get('query');
        $query = TextHelper::prepareQuery($raw);
        $order = 'name';
        $total = 0;
        $tabs = array();
        $result = null;
        if(3 <= mb_strlen($query)) {
            $result = $this->search($query, $order);
            if (!$result) {
                $query_lat = TextHelper::cyrToLat($query);
                $result = $this->search($query_lat, $order);
                if (!$result) {
                    $query_cyr = TextHelper::latToCyr($query);
                    $result = $this->search($query_cyr, $order);
                }
            }
        }
        if ($result) {
            $total = $result['total'];
            $tabs = $result['tabs'];
        } else {
            $not_found = new NotFound();
            $user_id = 1;
            if (Auth::check()) {
                $user_id = Auth::user()->id;
            }
            $isAdmin = RolesHelper::isAdmin($request);
            if(!$isAdmin) {
                $not_found->user_id = $user_id;
                $not_found->search = $query;
                $not_found->save();
                $type = "NotFound";
                $event = new Event();
                $event->event_type = 'Search';
                $event->element_type = $type;
                $event->element_id = 1;
                $event->user_id = $user_id;
                $event->name = 'Не найдено'; //«'.$search.'»';
                $event->text = '<a href="/search?query='.$query.'">'.$query.'</a>';
                $event->save();
            }
        }
        $options = array(
            'header' => true,
            'paginate' => false,
            'footer' => true,
            'sort_options' => array(),
            'sort' => 'name',
            'order' => 'asc',
        );
        return View::make(
            'sections.'.$this->section.'.section',
            array(
                'request' => $request,
                'query' => $query,
                'tabs' => $tabs,
                'total' => $total,
                'options' => $options,
            )
        );
    }

    /**
     * @param $query
     * @param $order
     * @return array|null
     */
    private function search($query, $order)
    {
        $limit = 100;
        $searchable = array(
            'persons' => array(
                'name' => 'Персоны',
                'section' => 'persons',
                'type' => 'Person',
                'fields' => array('name')
            ),
            'books' => array(
                'name' => 'Книги',
                'section' => 'books',
                'type' => 'Book',
                'fields' => array('name', 'alt_name')
            ),
            'films' => array(
                'name' => 'Фильмы',
                'section' => 'films',
                'type' => 'Film',
                'fields' => array('name', 'alt_name')
            ),
            'games' => array(
                'name' => 'Игры',
                'section' => 'games',
                'type' => 'Game',
                'fields' => array('name', 'alt_name')
            ),
            'albums' => array(
                'name' => 'Альбомы',
                'section' => 'albums',
                'type' => 'Album',
                'fields' => array('name')
            ),
            'band' => array(
                'name' => 'Группы',
                'section' => 'bands',
                'type' => 'Band',
                'fields' => array('name')
            ),
            'companies' => array(
                'name' => 'Компании',
                'section' => 'companies',
                'type' => 'Company',
                'fields' => array('name')
            ),
            'genres' => array(
                'name' => 'Жанры',
                'section' => 'genres',
                'type' => 'Genre',
                'fields' => array('name')
            ),
        );
        $tabs = array();
        $total = 0;
        foreach ($searchable as $entity) {
            $elements = $entity['type']::where(
                function ($sql) use ($entity, $query) {
                    foreach ($entity['fields'] as $field) {
                        $sql->orWhere($field, 'like', '%'.$query.'%');
                    }
                }
            )->orderBy($order)->limit($limit)->get();
            $count = count($elements);
            if (0 != $count) {
                $slug = $entity['section'];
                $name = $entity['name'];
                $count_string = ($limit <= $count) ? $count = (string)$count.'+' : (string)$count;
                $tabs[$slug]['slug'] = $slug;
                $tabs[$slug]['name'] = $name;
                $tabs[$slug]['count'] = $count_string;
                $tabs[$slug]['section'] = SectionsHelper::getSection($slug);
                $tabs[$slug]['elements'] = $elements;
                $total += (int) $count;
            }
        }
        if (0 != $total) {
            uasort($tabs, array('TextHelper', 'compareReverseCount'));
            return array('total' => $total, 'tabs' => $tabs);
        } else {
            return null;
        }
    }

    /**
     * @param  array  $result
     * @param  string  $query
     * @return array
     */
    private function getNameList(array $result = array(), string $query = '')
    {
        $limit = 3;

        $sections = array(
            'persons' => new Person(),
            'books' => new Book(),
            'films' => new Film(),
            'games' => new Game(),
            'albums' => new Album(),
            'bands' => new Band(),
        );

        if (!empty($query)) {
            foreach ($sections as $section => $elements) {
                $sections[$section] = $elements->where('name', 'like', '%'.$query.'%')
                    ->limit($limit)
                    ->pluck('name');
            }
            foreach ($sections as $section => $elements) {
                foreach ($elements as $key => $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function everythingJson(Request $request)
    {
        $result = array();

        $raw_query = urldecode($request->get('term'));
        $query = TextHelper::prepareQuery($raw_query);

        $result = $this->getNameList($result, $query);

        if (empty($result)) {
            $lat_query = TextHelper::latToCyr($query);
            $result = $this->getNameList($result, $lat_query);
        }

        if (empty($result)) {
            $cyr_query = TextHelper::cyrToLat($query);
            $result = $this->getNameList($result, $cyr_query);
        }

        return $result;
    }

    /**
     * @param  string  $query
     * @return array
     */
    private function getIDNameList(string $query = '')
    {
        $limit = 3;
        $result = array();
        $sections = array(
            'persons' => new Person(),
            'books' => new Book(),
            'films' => new Film(),
            'games' => new Game(),
            'albums' => new Album(),
            'bands' => new Band(),
        );
        if (!empty($query)) {
            foreach ($sections as $section => $elements) {
                $sections[$section] = $elements
                    ->where('name', 'like', '%'.$query.'%')
                    ->orWhere('alt_name', 'like', '%'.$query.'%')
                    ->limit($limit)
                    ->get();
            }
            foreach ($sections as $section => $elements) {
                foreach ($elements as $key => $value) {
                    $result[$section][$value->id]['id'] = $value->id;
                    $result[$section][$value->id]['name'] = $value->name;
                    $result[$section][$value->id]['alt_name'] = $value->alt_name;
                }
            }
        }
        return $result;
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function api(Request $request)
    {
        $raw_query = urldecode($request->get('query'));
        $query = TextHelper::prepareQuery($raw_query);

        $counter = new YaMetrika(env('YA_METRIKA')); // Номер счётчика Метрики
        $counter->hit($request->fullUrl());

        $data = $this->getIDNameList($query);

        $result['status'] = (count($data)) ? 'OK' : 'Not Found';
        $result['count'] = count($data);
        $result['data'] = $data;
        $result['url'] = $request->fullUrl();
        $result['errors'] = null;

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($result);
        die();
    }

}