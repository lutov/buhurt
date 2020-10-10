<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Helpers;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CommentsHelper {

	/**
	 * @param Request $request
	 * @param $comment
	 * @param bool $no_br
	 * @return string
	 */
	public static function render(Request $request, $comment, $no_br = false) {

		$user_id = $comment->user_id;
		$user = User::find($user_id);

		$user_options = UserHelper::getOptions($user);

		$is_my_private = in_array(1, $user_options);

		$rate = $comment->rate;

		$comments_text = '';

		if(!$is_my_private || (Auth::check() && $user_id == Auth::user()->id)) {


			$comments_text .= '<div class="card '.view('card.class').' mb-4" id="comment_' . $comment->id . '">';

            if (Auth::check() && $user_id == Auth::user()->id) {
                $comments_text .= '<div class="card-header text-right">';
                $comments_text .= '<div class="btn-group">';
                $comments_text .= '<span role="button" class="btn btn-sm btn-secondary" onclick="comment_edit(' . $comment->id . ')" title="Редактировать">&#9998;</span>';
                $comments_text .= '<span role="button" class="btn btn-sm btn-secondary" onclick="comment_delete(' . $comment->id . ')" title="Удалить">&#10006;</span>';
                $comments_text .= '</div>';
                $comments_text .= '</div>';
            }

			$comments_text .= '<div class="card-body">';

            $comments_text .= '<p class="card-text" id="comment_'.$comment->id.'_text">';
            if(!$no_br) {
                $comments_text .= nl2br($comment->comment);
            } else {
                $comments_text .= $comment->comment;
            }
            $comments_text .= '</p>';

            if($rate) {
                $comments_text .= '<p class="card-text">Оценка: '.$rate.'</p>';
            }

			$comments_text .= '</div>';

                $comments_text .= '<div class="card-footer small text-muted">';
                    $comments_text .= '<a href="/user/' . $user_id . '/profile">' . $comment->user->username . '</a>';
                    $comments_text .= ', ';
                    $comments_text .= Carbon::instance($comment->created_at)->diffForHumans();
                $comments_text .= '</div>';

			$comments_text .= '</div>';

			if ($no_br) {
				$comments_text = str_replace("\n", '<br />', $comments_text);
				$comments_text = addslashes($comments_text);
			}
		}

		return $comments_text;

	}

	/**
	 * @param $element
	 * @param int $user_id
	 * @return mixed
	 */
	public static function get($element, int $user_id = 0) {
		
		$sort = 'created_at';
		$order = 'desc';

		if($user_id) {
			$comments = $element->comments()
				->where('user_id', $user_id)
				->orderBy($sort, $order)
				->get()
			;
		} else {
			$comments = $element->comments()
				->orderBy($sort, $order)
				->get()
			;
		}

		return $comments;

	}

}
