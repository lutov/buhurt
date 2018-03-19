<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use Auth;

class RolesHelper {

	/**
	 * @return bool
	 */
	public static function is_admin() {

		$result = false;

		if(Auth::check()) {
			if('admin' == Auth::user()->roles()->first()->role) {
				$result = true;
			}
		}

		return $result;

	}

	/**
	 * @return bool
	 */
	public static function is_moderator() {

		$result = false;

		if(Auth::check()) {
			$role = Auth::user()->roles()->first()->role;
			if('admin' == $role || 'moderator' == $role) {
				$result = true;
			}
		}

		return $result;

	}

	/**
	 * @return bool
	 */
	public static function is_banned() {

		$result = false;

		if(Auth::check()) {
			if('banned' == Auth::user()->roles()->first()->role) {
				$result = true;
			}
		}

		return $result;

	}

}