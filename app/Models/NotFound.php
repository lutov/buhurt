<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotFound extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notfound';

	/**
	 * Отношение с
	 */
	public function user()
	{
		return $this->hasOne('\App\Models\User');
	}

}
