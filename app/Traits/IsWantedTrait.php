<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 15.03.2019
 * Time: 14:55
 */

namespace App\Traits;

use App\Models\User\Wanted;
use Illuminate\Support\Facades\Auth;

trait IsWantedTrait {

	private $w_field = 'wanted';
	private $w_value = 1;

	private $nw_field = 'not_wanted';
	private $nw_value = 1;

	/**
	 * @return bool
	 */
	public function isWanted() {

		$result = false;

		if(Auth::check()) {

			$section = $this->section();

			$wanted = Wanted::where('element_id', '=', $this->id)
				->where('element_type', '=', $section->type)
				->where($this->w_field, '=', $this->w_value)
				->first()
			;

			if($wanted) {

				$result = true;

			}

		}

		return $result;

	}

	/**
	 * @return bool
	 */
	public function isNotWanted() {

		$result = false;

		if(Auth::check()) {

			$section = $this->section();

			$not_wanted = Wanted::where('element_id', '=', $this->id)
				->where('element_type', '=', $section->type)
				->where($this->nw_field, '=', $this->nw_value)
				->first()
			;

			if($not_wanted) {

				$result = true;

			}

		}

		return $result;

	}

}