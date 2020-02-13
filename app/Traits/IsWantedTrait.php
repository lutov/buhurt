<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 15.03.2019
 * Time: 14:55
 */

namespace App\Traits;

use App\Helpers\SectionsHelper;
use App\Models\User\Unwanted;
use App\Models\User\Wanted;
use Illuminate\Support\Facades\Auth;

trait IsWantedTrait {

	/**
	 * @return bool
	 */
	public function isWanted() {

		$result = false;

		if(Auth::check()) {

			$section = SectionsHelper::getSection(SectionsHelper::getSectionBy(class_basename($this)));

			$wanted = Wanted::where('element_id', '=', $this->id)
				->where('user_id', '=', Auth::user()->id)
				->where('element_type', '=', $section->type)
				->first()
			;

			if($wanted) {$result = true;}

		}

		return $result;

	}

	/**
	 * @return bool
	 */
	public function isUnwanted() {

		$result = false;

		if(Auth::check()) {

			$section = SectionsHelper::getSection(SectionsHelper::getSectionBy(class_basename($this)));

			$unwanted = Unwanted::where('element_id', '=', $this->id)
				->where('user_id', '=', Auth::user()->id)
				->where('element_type', '=', $section->type)
				->first()
			;

			if($unwanted) {$result = true;}

		}

		return $result;

	}

}