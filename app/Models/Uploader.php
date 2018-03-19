<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uploader extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'uploaders_elements';

	/**
	 * Отношение с
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

}
