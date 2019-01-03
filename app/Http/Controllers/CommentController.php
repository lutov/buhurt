<?php namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Helpers\CommentsHelper;
use App\Models\Helpers\SectionsHelper;
use Illuminate\Http\Request;
use Input;
use Auth;
use EMTypograph;
use App\Models\Section;
use App\Models\Comment;
use App\Models\Helpers;
use LocalizedCarbon;

class CommentController extends Controller {

	protected $prefix = 'comments';

	/**
	 * @param Request $request
	 * @return string
	 */
	public function add(Request $request) {

		$text =  Input::get('comment');
		$section =  Input::get('section');
		$element =  Input::get('element');

		$type = SectionsHelper::getSectionType($section);

		$result = '';

		if(!empty($text)) {

			$text = EMTypograph::fast_apply($text, array(
				'Text.paragraphs' => 'off',
				'Text.breakline' => 'off',
				'OptAlign.all' => 'off',
				'Nobr.super_nbsp' => 'off'
			));
			$comment = new Comment();
			$comment->user_id = Auth::user()->id;
			$comment->element_type = $type;
			$comment->element_id = $element;
			$comment->comment = $text;
			$comment->save();
			
			$new_comment = CommentsHelper::render($request, $comment, true);

			$message = 'Комментарий сохранён';
			$result = '{"message":"'.$message.'", "comment_text":"'.$new_comment.'"}';

			$element = $type::find($element);
			$event = new Event();
			$event->event_type = 'Comment';
			$event->element_type = $type;
			$event->element_id = $element->id;
			$event->user_id = Auth::user()->id;
			$event->name = $element->name; //Auth::user()->username.' комментирует '.$element->name;
			$event->text = $text;
			$event->save();

		}

		return $result;
    }

	/**
	 * @param Request $request
	 * @return string
	 */
	public function edit(Request $request) {

		$text =  Input::get('comment');
		$section =  Input::get('section');
		$element =  Input::get('element');
		$id =  Input::get('id');

		$type = SectionsHelper::getSectionType($section);

		$result = '';

		if(!empty($text))  {

			$text = EMTypograph::fast_apply($text, array(
				'Text.paragraphs' => 'off',
				'Text.breakline' => 'off',
				'OptAlign.all' => 'off',
				'Nobr.super_nbsp' => 'off'
			));

			$comment = Comment::find($id);
			$comment->user_id = Auth::user()->id;
			$comment->element_type = $type;
			$comment->element_id = $element;
			$comment->comment = $text;
			$comment->save();

			$new_comment = CommentsHelper::render($request, $comment, true);

			$message = 'Комментарий сохранён';
			$result = '{"message":"'.$message.'", "comment_text":"'.$new_comment.'"}';

		}

		return $result;
    }

	/**
	 * @return string
	 */
	public function delete() {

		$id =  Input::get('id');

		$result = '';

		if(Auth::check())
		{

			$comment = Comment::find($id);
			$comment->delete();

			$message = 'Комментарий удален';
			$result = '{"message":"'.$message.'", "comment_text":""}';
		}

		return $result;

    }
}