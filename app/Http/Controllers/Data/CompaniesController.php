<?php namespace App\Http\Controllers\Data;

use App\Helpers\SectionsHelper;
use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\Data\Company;

class CompaniesController extends Controller {

	private $prefix = 'companies';

	public function list(Request $request) {

		$section = SectionsHelper::getSection($this->prefix);

		$sort = $request->get('sort', 'name');
		$order = $request->get('order', 'asc');
		$limit = 28;

		$sort = TextHelper::checkSort($sort);
		$order = TextHelper::checkOrder($order);

		$sort_options = array(
			'created_at' => 'Время добавления',
			'name' => 'Название',
		);

		$elements = Company::orderBy($sort, $order)
			->paginate($limit)
		;

		$options = array(
			'header' => true,
			'footer' => true,
			'paginate' => true,
			'sort_list' => $sort_options,
			'sort' => $sort,
			'order' => $order,
		);

		return View::make($this->prefix.'.index', array(
			'request' => $request,
			'elements' => $elements,
			'section' => $section,
			'options' => $options,
		));

	}
	
    public function item(Request $request, $id) {

		$section = SectionsHelper::getSection($this->prefix);

		$company = Company::find($id);

		if(isset($company->id)) {
			$logo = 0;
			$file_path = public_path() . '/data/img/covers/'.$section->alt_name.'/'.$id.'.jpg';
			if (file_exists($file_path)) {
				$logo = $id;
			}

			$sort = $request->get('sort', 'name');
			$order = $request->get('order', 'asc');
			$sort_options = array(
				'created_at' => 'Время добавления',
				'name' => 'Название',
				'year' => 'Год'
			);
			$limit = 28;

			$sort = TextHelper::checkSort($sort);
			$order = TextHelper::checkOrder($order);

			$books_published = $company->books_published()->orderBy($sort, $order)->paginate($limit);
			$games_developed = $company->games_developed()->orderBy($sort, $order)->paginate($limit);
			$games_published = $company->games_published()->orderBy($sort, $order)->paginate($limit);

			$options = array(
				'header' => true,
				'footer' => true,
				'paginate' => true,
				'sort_list' => $sort_options,
				'sort' => $sort,
				'order' => $order,
				'cover' => $logo,
			);

			return View::make($this->prefix . '.item', array(
				'request' => $request,
				'section' => $section,
				'element' => $company,
				'books_published' => $books_published,
				'games_developed' => $games_developed,
				'games_published' => $games_published,
				'options' => $options,
			));
		} else {
			// нет такой буквы
			return Redirect::to('/companies')->with('message', 'Нет такой компании');
		}
    }

	/**
	 * @param Request $request
	 * @param int $id
	 * @return mixed
	 */
	public function transfer(Request $request, int $id = 0) {

		$recipient_id = $request->get('recipient_id');

		DB::table('developers_games')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));
		DB::table('publishers_games')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));
		DB::table('publishers_books')->where('company_id', '=', $id)->update(array('company_id' => $recipient_id));

		DB::table('companies')->where('id', '=', $id)->delete();//->update(array('name' => ''));

		return Redirect::to('/'.$this->prefix.'/'.$recipient_id);

	}
}