<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Models\Helpers;

use Auth;
use Illuminate\Http\Request;

class RolesHelper {

	/**
	 * @param Request $request
	 * @param string $role
	 * @return bool|mixed
	 */
	private static function is(Request $request, string $role = '') {

		$result = false;

		if (Auth::check()) {

			if($request->session()->exists($role)) {

				$result = session($role);

			} else {

				if ($role == Auth::user()->roles()->first()->role) {

					$result = true;
					session([$role => true]);

				} else {

					session([$role => false]);
					$result = false;

				}

			}

		} else {

			session([$role => false]);
			$result = false;

		}

		return $result;

	}

	/**
	 * @param Request $request
	 * @return bool
	 */
	public static function isAdmin(Request $request) {

		$role = 'admin';

		$result = RolesHelper::is($request, $role);

		return $result;

	}

	/**
	 * @param Request $request
	 * @return bool
	 */
	public static function isModerator(Request $request) {

		$role = 'moderator';

		$result = RolesHelper::is($request, $role);

		return $result;

	}

	/**
	 * @param Request $request
	 * @return bool
	 */
	public static function isBanned(Request $request) {

		$role = 'banned';

		$result = RolesHelper::is($request, $role);

		return $result;

	}

}