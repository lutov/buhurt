<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use App\Models\User;
use Auth;
use Form;
use LocalizedCarbon;

class CommentsHelper {

	/**
	 * @param $comments
	 * @return string
	 */
	public static function get($comments) {

		//echo '<pre>'.print_r($comments, true).'</pre>';

		$comments_list = '';

		foreach($comments as $key => $comment) {

			$comments_list .= CommentsHelper::render($comment);

		}

		return $comments_list;

	}

	/**
	 * @param $comment
	 * @param bool $no_br
	 * @return string
	 */
	public static function render($comment, $no_br = false) {

		$user_id = $comment->user_id;
		$user = User::find($user_id);

		$user_options = $user
			->options()
			->where('enabled', '=', 1)
			->pluck('option_id')
			->toArray();

		$is_my_private = in_array(1, $user_options);

		$comments_text = '';

		if(!$is_my_private || (Auth::check() && $user_id == Auth::user()->id)) {

			$file_path = public_path() . '/data/img/avatars/' . $user_id . '.jpg';

			$comments_text .= '<div class="container-fluid border bg-light mt-3" id="comment_' . $comment->id . '">';

			$comments_text .= '<div class="row mt-3 mb-3">';

				$comments_text .= '<div class="col-md-6">';

					$comments_text .= '<a href="/user/' . $user_id . '/profile">' . $comment->user->username . '</a>';
					$comments_text .= ', ';
					$comments_text .= LocalizedCarbon::instance($comment->created_at)->diffForHumans();

				$comments_text .= '</div>';

				if (Auth::check() && $user_id == Auth::user()->id) {

					$comments_text .= '<div class="col-md-6 text-right">';
					$comments_text .= '<div class="btn-group">';
					$comments_text .= '<span role="button" class="btn btn-sm btn-outline-success" onclick="comment_edit(' . $comment->id . ')" title="Редактировать">&#9998;</span>';
					$comments_text .= '<span role="button" class="btn btn-sm btn-outline-danger" onclick="comment_delete(' . $comment->id . ')" title="Удалить">&#10006;</span>';
					$comments_text .= '</div>';
					$comments_text .= '</div>';

				}

			$comments_text .= '</div>';

			$comments_text .= '<div class="row mt-3 mb-3">';

				$comments_text .= '<div class="col-md-2">';

					if (file_exists($file_path)) {
						$comments_text .= '<a href="/user/' . $user_id . '/profile"><img src="/data/img/avatars/' . $user_id . '.jpg" width="" alt="" class="img-fluid border" /></a>';
					}

				$comments_text .= '</div>';

				$comments_text .= '<div class="col-md-10" id="comment_' . $comment->id . '_text">';

					$comments_text .= '<p class="p-3 bg-white border">'.nl2br($comment->comment).'</p>';

				$comments_text .= '</div>';

			$comments_text .= '</div>';

			$comments_text .= '</div>';

			if ($no_br) {
				$comments_text = preg_replace('/\n/', '', $comments_text);
				$comments_text = preg_replace('/"/', '\"', $comments_text);
			}
		}

		return $comments_text;

	}


	/**
	 * @return string
	 */
	public static function showCommentForm (){

		if(Auth::check()) {

			$form = '';

			$form .= '<span role="button" class="btn btn-primary" onclick="show_comment_form();">Написать комментарий</span>';

			$form .= Form::open(array('action' => 'CommentController@add', 'class' => 'comment_form', 'method' => 'POST'));

			$form .= '<div class="mt-3">';
			$form .= Form::textarea('comment', $value = null, $attributes = array(
				'placeholder' => 'Комментарий',
				'class' => 'w-100',
				'id' => 'comment'
			));
			$form .= Form::hidden('comment_id', $value = null, $attributes = array('id' => 'comment_id', 'autocomplete' => 'off'));
			$form .= '</div>';

			$form .= '<div class="text-right">';
			$form .= Form::button('Сохранить', $attributes = array(
				'id' => 'comment_save',
				'role' => 'button',
				'class' => 'btn btn-secondary',
			));
			$form .= Form::close();
			$form .= '</div>';

			return $form;

		} else {

			return DummyHelper::reg2comment();

		}

	}

	/**
	 * @param $comments
	 * @return string
	 */
	public static function showComments($comments) {

		//echo '<pre>'.print_r($comments, true).'</pre>';

		$comments_list = '';

		foreach($comments as $key => $comment) {

			$comments_list .= CommentsHelper::render($comment);

		}

		return $comments_list;

	}

}