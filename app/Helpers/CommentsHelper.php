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
	 * @param Request $request
	 * @param string $section
	 * @param int $element_id
	 * @return string
	 */
	public static function showCommentForm (Request $request, string $section = '', int $element_id = 0) {

		if(Auth::check()) {

			$form = '';

			$form .= '<span role="button" class="btn btn-primary" data-toggle="collapse" data-target="#comment_form" aria-expanded="false" aria-controls="comment_form">Написать комментарий</span>';

			$form .= Form::open(array(
				'action' => 'User\CommentController@add',
				'class' => 'collapse',
				'method' => 'POST',
				'id' => 'comment_form'
			));

			$form .= '<div class="mt-3">';
			$form .= Form::textarea('comment', $value = null, $attributes = array(
				'placeholder' => 'Комментарий',
				'class' => 'w-100 border rounded p-3',
				'id' => 'comment'
			));
			$form .= Form::hidden('comment_id', $value = null, $attributes = array('id' => 'comment_id', 'autocomplete' => 'off'));
			$form .= '</div>';

			$form .= '<div class="text-right">';
			$form .= Form::button('Сохранить', $attributes = array(
				'id' => 'comment_save',
				'role' => 'button',
				'class' => 'btn btn-secondary',
				'onclick' => 'comment_add(\''.$section.'\', \''.$element_id.'\')'
			));
			$form .= Form::close();
			$form .= '</div>';

			return $form;

		} else {

			return DummyHelper::regToComment();

		}

	}

	/**
	 * @param Request $request
	 * @param $comments
	 * @return string
	 */
	public static function showComments(Request $request, $comments) {
		$comments_list = '';
		foreach($comments as $key => $comment) {
			$comments_list .= self::render($request, $comment);
		}
		return $comments_list;
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