<?php namespace App\Models;

use Eloquent;
//use SleepingOwl\Models\SleepingOwlModel;

//class Company extends SleepingOwlModel  {
class Company extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'companies';

	protected $morphClass = 'Company';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name','alt_name', 'description');

	/**
	 * Отношение с книгами
	 */
	public function books_published()
	{
		return $this->belongsToMany('App\Models\Book', 'publishers_books', 'company_id', 'book_id');
	}

	/**
	 * Разработчики
	 */
	public function games_developed()
	{
		return $this->belongsToMany('App\Models\Game', 'developers_games', 'company_id', 'game_id');
	}

	/**
	 * Издатели игр
	 */
	public function games_published()
	{
		return $this->belongsToMany('App\Models\Game', 'publishers_games', 'company_id', 'game_id');
	}


}
