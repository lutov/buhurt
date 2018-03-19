<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'options';


	//public $timestamps = false;


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
