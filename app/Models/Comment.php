<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	protected $softDelete = true;

	public function element()
	{
		return $this->morphTo();
	}
	
	/**
	 * Отношение с пользователями
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

}
