<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Event;
use App\Helpers\CommentsHelper;
use App\Helpers\SectionsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use EMTypograph;
use App\Models\User\Comment;

class CommentController extends Controller {

	protected $prefix = 'comments';

	/**
	 * @param Request $request
	 * @return string
	 */
	public function add(Request $request) {

		$text =  $request->get('comment');
		$section =  $request->get('section');
		$element =  $request->get('element');

		$type = SectionsHelper::getSectionType($section);

		$result = '';

		if(!empty($text)) {

			$text = strip_tags($text);

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

		$text =  $request->get('comment');
		$section =  $request->get('section');
		$element =  $request->get('element');
		$id =  $request->get('id');

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
	 * @param Request $request
	 * @return string
	 * @throws \Exception
	 */
	public function delete(Request $request) {

		$id =  $request->get('id');

		$result = '';

		if(Auth::check()) {

			$comment = Comment::find($id);
			$comment->delete();

			$message = 'Комментарий удален';
			$result = '{"message":"'.$message.'", "comment_text":""}';
		}

		return $result;

    }
}