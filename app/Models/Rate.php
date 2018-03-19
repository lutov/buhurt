<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rates';


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

	/**
	 * Отношение с книгами
	 */
	public function book()
	{
		//return $this->morphMany('Book', 'section');
	}

}
