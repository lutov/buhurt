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

			$comments_text .= '<div class="comment" id="comment_' . $comment->id . '">';

			if (Auth::check() && $user_id == Auth::user()->id) {
				$comments_text .= '<div class="comment_controls">';
				$comments_text .= '<p  class="symlink" onclick="comment_edit(' . $comment->id . ')">Редактировать</p>';
				$comments_text .= ' | ';
				$comments_text .= '<p  class="symlink" onclick="comment_delete(' . $comment->id . ')">Удалить</p>';
				$comments_text .= '</div>';
			}

			$comments_text .= '<div class="comment_info">';
			$comments_text .= '<p><a href="/user/' . $user_id . '/profile">' . $comment->user->username . '</a><p>';
			$comments_text .= '<p class="comment_date">' . LocalizedCarbon::instance($comment->created_at)->diffForHumans() . ':</p>';
			$comments_text .= '</div>';

			$comments_text .= '<div class="comment_body">';
			$comments_text .= '<div class="comment_avatar">';
			if (file_exists($file_path)) {
				$comments_text .= '<a href="/user/' . $user_id . '/profile"><img src="/data/img/avatars/' . $user_id . '.jpg" alt=""/></a><br/>';
			}
			$comments_text .= '</div>';
			$comments_text .= '<div class="comment_text" id="comment_' . $comment->id . '_text">';
			$comments_text .= nl2br($comment->comment);
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

			$form = '
				<div class="comment_add">
					<span class="symlink" onclick="show_comment_form();">Написать комментарий</span>
					<div id="comment_form">
						'.Form::open(array('action' => 'CommentController@add', 'class' => 'comment_form', 'method' => 'POST')).'
							'.Form::textarea('comment', $value = null, $attributes = array('placeholder' => 'Комментарий', 'class' => 'half', 'id' => 'comment')).'
							'.Form::hidden('comment_id', $value = null, $attributes = array('id' => 'comment_id', 'autocomplete' => 'off')).'
							<br/>
							'.Form::button('Сохранить', $attributes = array('id' => 'comment_save')).'
						'.Form::close().'
					</div>
				</div>';

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