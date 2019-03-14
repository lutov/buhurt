<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', array(
	'as' => 'home',
	'uses' => 'HomeController@index'
));

Route::get('about', array(
	'as' => 'about',
	'uses' => 'HomeController@about'
));

Route::get('icons', array(
	'as' => 'icons',
	'uses' => 'HomeController@icons'
));

// Base
Route::any('base/{section}/', function($section) {return Redirect::to('/'.$section.'/');});
Route::any('base/{section}/{id}/', function($section, $id) {return Redirect::to('/'.$section.'/'.$id.'/');});

// No "base"
Route::any('books', array(
	'as' => 'books',
	'uses' => 'Data\BooksController@list'
));
Route::get('books/{id}', array(
	'as' => 'Book',
	'uses' => 'Data\BooksController@item')
)->where('id', '[0-9]+'); // только числа
Route::get('books/random', 	array('uses' => 'Search\RandomController@books'));

Route::any('films', array(
	'as' => 'films',
	'uses' => 'Data\FilmsController@list'
));
Route::get('films/{id}', array(
	'as' => 'Film',
	'uses' => 'Data\FilmsController@item'
))->where('id', '[0-9]+');

Route::any('games', array(
	'as' => 'games',
	'uses' => 'Data\GamesController@list'
));
Route::get('games/{id}', array(
	'as' => 'Game',
	'uses' => 'Data\GamesController@item'
))->where('id', '[0-9]+');

Route::any('albums', array(
	'as' => 'albums',
	'uses' => 'Data\AlbumsController@list'
));
Route::get('albums/{id}', array(
	'as' => 'Album',
	'uses' => 'Data\AlbumsController@item'
))->where('id', '[0-9]+');

Route::get('memes', array(
	'as' => 'memes',
	'uses' => 'Data\MemesController@list'
));
Route::any('memes/{id}', array(
	'as' => 'Meme',
	'uses' => 'Data\MemesController@item'
))->where('id', '[0-9]+');

Route::get('persons', array('uses' => 'Data\PersonsController@list'));
Route::any('persons/{id}', array('uses' => 'Data\PersonsController@item'))->where('id', '[0-9]+');

Route::get('bands', array('uses' => 'Data\BandsController@list'));
Route::any('bands/{id}', array('uses' => 'Data\BandsController@item'))->where('id', '[0-9]+');

Route::get('companies', array('uses' => 'Data\CompaniesController@list'));
Route::any('companies/{id}', array('uses' => 'Data\CompaniesController@item'))->where('id', '[0-9]+');

//Admin
Route::group(array('middleware' => 'admin'), function() {

	Route::group(array('prefix' => 'admin'), function() {

		Route::get('add', array('uses' => 'Admin\DatabaseController@add'));
		Route::get('add/{section}', array('uses' => 'Admin\DatabaseController@add'));
		Route::get('edit/{section}/{id}', array('uses' => 'Admin\DatabaseController@edit'));
		Route::post('save', array('uses' => 'Admin\DatabaseController@save'));
		Route::any('delete/{section}/{id}', array('uses' => 'Admin\DatabaseController@delete'));

		Route::any('transfer/persons/{id}', array('uses' => 'Data\PersonsController@transfer'));
		Route::any('transfer/companies/{id}', array('uses' => 'Data\CompaniesController@transfer'));

		Route::any('transfer/albums/{id}', array('uses' => 'Data\AlbumsController@transfer'));
		Route::any('transfer/books/{id}', array('uses' => 'Data\BooksController@transfer'));
		Route::any('transfer/films/{id}', array('uses' => 'Data\FilmsController@transfer'));
		Route::any('transfer/games/{id}', array('uses' => 'Data\GamesController@transfer'));

	});

});
Route::get('q_add/{section}', array('uses' => 'Admin\DatabaseController@q_add'));
	
// Relations
Route::any('{section}/{id}/relations', array('uses' => 'Search\RelationsController@getRelations'));
Route::any('{section}/{id}/relations/add', array('uses' => 'Search\RelationsController@addRelation'));
Route::any('{section}/{id}/relations/edit', array('uses' => 'Search\RelationsController@editRelation'));
Route::any('{section}/{id}/relations/delete', array('uses' => 'Search\RelationsController@deleteRelation'));

// Years
Route::group(array('prefix' => 'years'), function() {
	Route::any('/', array('uses' => 'Data\YearsController@sections'));
	Route::any('{section}', array('uses' => 'Data\YearsController@list'));
	Route::any('{section}/{year}', array('uses' => 'Data\YearsController@item'))->where('year', '[0-9]+');
});

// genres
Route::group(array('prefix' => 'genres'), function() {
	Route::any('/', array('uses' => 'Data\GenresController@sections'));
	Route::any('{section}', array('uses' => 'Data\GenresController@list'));
	Route::any('{section}/{id}', array('uses' => 'Data\GenresController@item'));
});

// platforms
Route::group(array('prefix' => 'platforms'), function() {
	Route::any('/', array('uses' => 'Data\PlatformsController@list'));
	Route::any('{id}', array('uses' => 'Data\PlatformsController@item'));
	Route::any('games/{id}', function($id) {return Redirect::to('/platforms/'.$id.'/');});
});

// countries
Route::group(array('prefix' => 'countries'), function() {
	Route::any('/', array('uses' => 'Data\CountriesController@list'));
	Route::any('{id}', array('uses' => 'Data\CountriesController@item'));
	Route::any('films/{id}', function($id) {return Redirect::to('/countries/'.$id.'/');});
});
	
// collections
Route::group(array('prefix' => 'collections'), function() {
	Route::any('/', array('uses' => 'Data\CollectionsController@list'));
	Route::any('{id}', array('uses' => 'Data\CollectionsController@item'));
});


// Auth manipulations
Route::group(array('middleware' => 'auth'), function() {
	// Rates
	Route::group(array('prefix' => 'rates'), function () {
		Route::post('rate/{section}/{id}', array('uses' => 'User\RatesController@rate'));
		Route::post('unrate/{section}/{id}', array('uses' => 'User\RatesController@unrate'));
	});

	// Wanted & Unwanted
	Route::post('like/{section}/{id}', array('uses' => 'User\WantedController@like'))->where('id', '[0-9]+');
		Route::post('unlike/{section}/{id}', array('uses' => 'User\WantedController@unlike'))->where('id', '[0-9]+');
	Route::post('dislike/{section}/{id}', array('uses' => 'User\WantedController@dislike'))->where('id', '[0-9]+');
		Route::post('undislike/{section}/{id}', array('uses' => 'User\WantedController@undislike'))->where('id', '[0-9]+');

	// Achievements
	Route::group(array('prefix' => 'achievements'), function () {
		Route::any('/', array('uses' => 'User\AchievementsController@check'));
	});
});


// Search
Route::group(array('prefix' => 'search'), function() {

	Route::get('', array('uses' => 'Search\SearchController@everything'));

	Route::get('json', array('uses' => 'Search\SearchController@everythingJson'));

	Route::get('person_name', array('uses' => 'Search\TipsController@person_name'));
	Route::get('company_name', array('uses' => 'Search\TipsController@company_name'));
	Route::get('country_name', array('uses' => 'Search\TipsController@country_name'));
	Route::get('collection_name', array('uses' => 'Search\TipsController@collection_name'));
	Route::get('platform_name', array('uses' => 'Search\TipsController@platform_name'));

	Route::get('book_name', array('uses' => 'Search\TipsController@book_name'));
	Route::get('book_genre', array('uses' => 'Search\TipsController@book_genre'));

	Route::get('film_name', array('uses' => 'Search\TipsController@film_name'));
	Route::get('film_genre', array('uses' => 'Search\TipsController@film_genre'));

	Route::get('game_name', array('uses' => 'Search\TipsController@game_name'));
	Route::get('game_genre', array('uses' => 'Search\TipsController@game_genre'));

	Route::get('album_name', array('uses' => 'Search\TipsController@album_name'));
	Route::get('album_genre', array('uses' => 'Search\TipsController@album_genre'));
	Route::get('band_name', array('uses' => 'Search\TipsController@band_name'));

	Route::get('meme_name', array('uses' => 'Search\TipsController@meme_name'));
	Route::get('meme_genre', array('uses' => 'Search\TipsController@meme_genre'));

});

// User
Route::group(array('prefix' => 'user'), function() {
	Route::get('/', function() {return Redirect::to('/users');});

	Route::get('logout', array('uses' => 'User\UserController@logout'));

	Route::group(array('middleware' => 'guest',), function() {

		Route::get('register', array('uses' => 'User\UserController@register'));
		Route::post('register', array('uses' => 'User\UserController@store'));

		Route::get('login', array('uses' => 'User\UserController@index'));
		Route::post('login', array('uses' => 'User\UserController@login'));

	});

	Route::get('{id}/', function($id) {return Redirect::to('/user/'.$id.'/profile');});
	Route::get('{id}/profile', array('uses' => 'User\UserController@view'));
	Route::any('{id}/rates/{section}', array('uses' => 'User\UserController@rates'));
	Route::any('{id}/rates/{section}/export', array('uses' => 'User\UserController@rates_export'));

	Route::any('{id}/wanted/{section}', array('uses' => 'User\UserController@wanted'));
	Route::any('{id}/not_wanted/{section}', array('uses' => 'User\UserController@not_wanted'));

	Route::post('avatar', array('uses' => 'User\UserController@avatar'));

	Route::any('change_password', array('uses' => 'User\UserController@change_password'));
	
	Route::any('vk_auth', array('uses' => 'User\UserController@vk_auth'));

	Route::any('{id}/options', array('uses' => 'User\UserController@options'));

	Route::any('{id}/recommendations', array('uses' => 'User\RecommendationsController@get'));
});
Route::get('users/', array('uses' => 'User\UserController@list'));

Route::any('recommendations', array('uses' => 'User\RecommendationsController@gag'));

/* LISTS */
Route::group(array('prefix' => 'lists'), function() {

	/* LISTS LIST */
	Route::any('get_lists', array('uses' => 'User\ListsController@getLists'));

	/* LIST */
	Route::any('add_list', array('uses' => 'User\ListsController@addList'));
	Route::any('edit_list', array('uses' => 'User\ListsController@editList'));
	Route::any('remove_list', array('uses' => 'User\ListsController@removeList'));
	Route::any('get_list', array('uses' => 'User\ListsController@getList'));

	/* LIST ELEMENT */
	Route::any('add_to_lists', array('uses' => 'User\ListsController@addToList'));
	Route::any('remove_from_lists', array('uses' => 'User\ListsController@removeFromList'));

});

// Comments
Route::group(array('prefix' => 'comment'), function() {
	Route::post('add', array('uses' => 'User\CommentController@add'));
	Route::post('edit', array('uses' => 'User\CommentController@edit'));
	Route::post('delete', array('uses' => 'User\CommentController@delete'));
});

// Events
Route::group(array('prefix' => 'events'), function() {
	Route::get('/', array('uses' => 'User\EventsController@getList'));
});

Route::any('demo', array('uses' => 'Admin\DemoController@index'));
