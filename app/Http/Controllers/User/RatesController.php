<?php namespace App\Http\Controllers\User;

use App\Helpers\SectionsHelper;
use App\Http\Controllers\Controller;
use App\Models\Data\Album;
use App\Models\Data\Book;
use App\Models\Data\Film;
use App\Models\Data\Game;
use App\Models\User\Event;
use App\Models\User\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RatesController extends Controller
{

    private $prefix = '';

    /**
     * @return mixed
     */
    public function goHome()
    {
        return Redirect::to('/');
    }

    /**
     * @param  Request  $request
     * @param  string  $section
     * @param  string  $id
     */
    public function rate(Request $request, string $section = '', string $id = '')
    {
        $result = false;
        $rate_val = $request->get('rate_val', 0);
        $type = SectionsHelper::getSectionType($section);
        $element = $type::find($id);
        //die(print_r($element, true));
        if (!empty($element)) {
            // Выбор пользователя
            $user = Auth::user();
            $exists = $element->rates()->where('user_id', '=', $user->id)->first();
            //die(print_r($exists, true));
            if (isset($exists->id)) {
                $rate = Rate::find($exists->id);
                if ($rate->rate != $rate_val) {
                    $rate->rate = $rate_val;
                    $rate->save();
                    $result = true;
                }
            } else {
                // Создание новой оценки
                $rate = new Rate();
                $rate->rate = $rate_val;
                $rate->element_id = $id;
                $rate->element_type = $type;
                // Сохранение оценки
                $user->rates()->save($rate);
                $result = true;
            }
            if ($result) {
                $event = new Event();
                $event->event_type = 'Rate';
                $event->element_type = $type;
                $event->element_id = $id;
                $event->user_id = $user->id;
                $event->name = $element->name; //$user->username.' оценивает '.$element->name.' на '.$rate_val;
                $event->text = $rate_val;
                $event->save();
                // success
                $output = array(
                    "type" => "rate",
                    "title" => "Оценка",
                    "message" => "Оценка&nbsp;сохранена",
                    "images" => array(),
                    "status" => "OK",
                );
                //echo '{"msg_type": "rate", "message": "Оценка&nbsp;сохранена", "status":"OK"}';
                echo json_encode($output);
            }
        }
    }

    /**
     * @param $section
     * @param $id
     * @throws \Exception
     */
    public function unrate($section, $id)
    {
        if ('books' == $section) {
            $element = Book::find($id);
            $type = 'Book';
        } elseif ('films' == $section) {
            $element = Film::find($id);
            $type = 'Film';
        } elseif ('games' == $section) {
            $element = Game::find($id);
            $type = 'Game';
        } elseif ('albums' == $section) {
            $element = Album::find($id);
            $type = 'Album';
        } else {
            $element = array();
        }

        if (!empty($element)) {
            // Выбор пользователя
            $user = Auth::user();

            $exists = $element->rates()->where('user_id', '=', $user->id)->first();
            //die(print_r($exists, true));
            if (isset($exists->id)) {
                $rate = Rate::find($exists->id);
                $rate->delete();
                //$rate->rate = $rate_val;
                //$rate->save();

                // success
                $result = array(
                    "type" => "rate",
                    "title" => "Оценка",
                    "message" => "Оценка&nbsp;удалена",
                    "images" => array(),
                    "status" => "OK",
                );
                echo json_encode($result);
            } else {
                // fail
                $result = array(
                    "type" => "rate",
                    "title" => "Оценка",
                    "message" => "Оценка&nbsp;не найдена",
                    "images" => array(),
                    "status" => "OK",
                );
                echo json_encode($result);
            }
        }
        //return Redirect::back()->with('message', 'Оценка удалена');

    }

}