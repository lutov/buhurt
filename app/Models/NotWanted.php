<?php

class NotWanted extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'not_wanted';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	protected $softDelete = true;

	/**
	 * Отношение с пользователями
	 */
	public function user()
	{
		//return $this->belongsTo('User');
	}	
	
	/**
	 * Отношение с книгами
	 */
	public function book()
	{
		return $this->belongsTo('App\Models\Book');
	}

}
