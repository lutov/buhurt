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
	'uses' => 'BooksController@list'
));
Route::get('books/{id}', array(
	'as' => 'Book',
	'uses' => 'BooksController@item')
)->where('id', '[0-9]+'); // только числа
Route::get('books/random', 	array('uses' => 'RandomController@books'));

Route::any('films', array(
	'as' => 'films',
	'uses' => 'FilmsController@list'
));
Route::get('films/{id}', array(
	'as' => 'Film',
	'uses' => 'FilmsController@item'
))->where('id', '[0-9]+');

Route::any('games', array(
	'as' => 'games',
	'uses' => 'GamesController@list'
));
Route::get('games/{id}', array(
	'as' => 'Game',
	'uses' => 'GamesController@item'
))->where('id', '[0-9]+');

Route::any('albums', array(
	'as' => 'albums',
	'uses' => 'AlbumsController@list'
));
Route::get('albums/{id}', array(
	'as' => 'Album',
	'uses' => 'AlbumsController@item'
))->where('id', '[0-9]+');

Route::get('memes', array(
	'as' => 'memes',
	'uses' => 'MemesController@list'
));
Route::any('memes/{id}', array(
	'as' => 'Meme',
	'uses' => 'MemesController@item'
))->where('id', '[0-9]+');

Route::get('persons', array('uses' => 'PersonsController@show_all'));
Route::any('persons/{id}', array('uses' => 'PersonsController@show_item'))->where('id', '[0-9]+');

Route::get('bands', array('uses' => 'BandsController@show_all'));
Route::any('bands/{id}', array('uses' => 'BandsController@show_item'))->where('id', '[0-9]+');

Route::get('companies', array('uses' => 'CompaniesController@show_all'));
Route::any('companies/{id}', array('uses' => 'CompaniesController@show_item'))->where('id', '[0-9]+');

//Admin
Route::group(array('middleware' => 'admin'), function() {

	Route::group(array('prefix' => 'admin'), function() {

		Route::get('add', array('uses' => 'DatabaseController@add'));
		Route::get('add/{section}', array('uses' => 'DatabaseController@add'));
		Route::get('edit/{section}/{id}', array('uses' => 'DatabaseController@edit'));
		Route::post('save', array('uses' => 'DatabaseController@save'));
		Route::any('delete/{section}/{id}', array('uses' => 'DatabaseController@delete'));

		Route::any('transfer/persons/{id}', array('uses' => 'PersonsController@transfer'));
		Route::any('transfer/companies/{id}', array('uses' => 'CompaniesController@transfer'));

		Route::any('transfer/albums/{id}', array('uses' => 'AlbumsController@transfer'));
		Route::any('transfer/books/{id}', array('uses' => 'BooksController@transfer'));
		Route::any('transfer/films/{id}', array('uses' => 'FilmsController@transfer'));
		Route::any('transfer/games/{id}', array('uses' => 'GamesController@transfer'));

	});

});
Route::get('q_add/{section}', array('uses' => 'DatabaseController@q_add'));
	
// Relations
Route::any('{section}/{id}/relations', array('uses' => 'RelationsController@getRelations'));
Route::any('{section}/{id}/relations/add', array('uses' => 'RelationsController@addRelation'));
Route::any('{section}/{id}/relations/edit', array('uses' => 'RelationsController@editRelation'));
Route::any('{section}/{id}/relations/delete', array('uses' => 'RelationsController@deleteRelation'));

// Years
Route::group(array('prefix' => 'years'), function() {
	Route::any('/', array('uses' => 'YearsController@sections'));
	Route::any('{section}', array('uses' => 'YearsController@list'));
	Route::any('{section}/{year}', array('uses' => 'YearsController@item'))->where('year', '[0-9]+');
});

// genres
Route::group(array('prefix' => 'genres'), function() {
	Route::any('/', array('uses' => 'GenresController@sections'));
	Route::any('{section}', array('uses' => 'GenresController@list'));
	Route::any('{section}/{id}', array('uses' => 'GenresController@show_item'));
});

// platforms
Route::group(array('prefix' => 'platforms'), function() {
	Route::any('/', array('uses' => 'PlatformsController@list'));
	Route::any('{id}', array('uses' => 'PlatformsController@item'));
	Route::any('games/{id}', function($id) {return Redirect::to('/platforms/'.$id.'/');});
});

// countries
Route::group(array('prefix' => 'countries'), function() {
	Route::any('/', array('uses' => 'CountriesController@list'));
	Route::any('{id}', array('uses' => 'CountriesController@item'));
	Route::any('films/{id}', function($id) {return Redirect::to('/countries/'.$id.'/');});
});
	
// collections
Route::group(array('prefix' => 'collections'), function() {
	Route::any('/', array('uses' => 'CollectionsController@list'));
	Route::any('{id}', array('uses' => 'CollectionsController@item'));
});


// Auth manipulations
Route::group(array('middleware' => 'auth'), function() {
	// Rates
	Route::group(array('prefix' => 'rates'), function () {
		Route::post('rate/{section}/{id}', array('uses' => 'RatesController@rate'));
		Route::post('unrate/{section}/{id}', array('uses' => 'RatesController@unrate'));
	});

	// Wanted & Unwanted
	Route::post('like/{section}/{id}', array('uses' => 'WantedController@like'))->where('id', '[0-9]+');
		Route::post('unlike/{section}/{id}', array('uses' => 'WantedController@unlike'))->where('id', '[0-9]+');
	Route::post('dislike/{section}/{id}', array('uses' => 'WantedController@dislike'))->where('id', '[0-9]+');
		Route::post('undislike/{section}/{id}', array('uses' => 'WantedController@undislike'))->where('id', '[0-9]+');

	// Achievements
	Route::group(array('prefix' => 'achievements'), function () {
		Route::any('/', array('uses' => 'AchievementsController@check'));
	});
});


// Search
Route::group(array('prefix' => 'search'), function() {

	Route::get('', array('uses' => 'SearchController@everything'));

	Route::get('json', array('uses' => 'SearchController@everythingJson'));

	Route::get('person_name', array('uses' => 'TipsController@person_name'));
	Route::get('company_name', array('uses' => 'TipsController@company_name'));
	Route::get('country_name', array('uses' => 'TipsController@country_name'));
	Route::get('collection_name', array('uses' => 'TipsController@collection_name'));
	Route::get('platform_name', array('uses' => 'TipsController@platform_name'));

	Route::get('book_name', array('uses' => 'TipsController@book_name'));
	Route::get('book_genre', array('uses' => 'TipsController@book_genre'));

	Route::get('film_name', array('uses' => 'TipsController@film_name'));
	Route::get('film_genre', array('uses' => 'TipsController@film_genre'));

	Route::get('game_name', array('uses' => 'TipsController@game_name'));
	Route::get('game_genre', array('uses' => 'TipsController@game_genre'));

	Route::get('album_name', array('uses' => 'TipsController@album_name'));
	Route::get('album_genre', array('uses' => 'TipsController@album_genre'));
	Route::get('band_name', array('uses' => 'TipsController@band_name'));

	Route::get('meme_name', array('uses' => 'TipsController@meme_name'));
	Route::get('meme_genre', array('uses' => 'TipsController@meme_genre'));

});

// User
Route::group(array('prefix' => 'user'), function() {
	Route::get('/', function() {return Redirect::to('/users');});

	Route::get('logout', array('uses' => 'UserController@logout'));

	Route::group(array('middleware' => 'guest',), function() {

		Route::get('register', array('uses' => 'UserController@register'));
		Route::post('register', array('uses' => 'UserController@store'));

		Route::get('login', array('uses' => 'UserController@index'));
		Route::post('login', array('uses' => 'UserController@login'));

	});

	Route::get('{id}/', function($id) {return Redirect::to('/user/'.$id.'/profile');});
	Route::get('{id}/profile', array('uses' => 'UserController@view'));
	Route::any('{id}/rates/{section}', array('uses' => 'UserController@rates'));
	Route::any('{id}/rates/{section}/export', array('uses' => 'UserController@rates_export'));

	Route::any('{id}/wanted/{section}', array('uses' => 'UserController@wanted'));
	Route::any('{id}/not_wanted/{section}', array('uses' => 'UserController@not_wanted'));

	Route::post('avatar', array('uses' => 'UserController@avatar'));

	Route::any('change_password', array('uses' => 'UserController@change_password'));
	
	Route::any('vk_auth', array('uses' => 'UserController@vk_auth'));

	Route::any('{id}/options', array('uses' => 'UserController@options'));

	Route::any('{id}/recommendations', array('uses' => 'RecommendationsController@get'));
});
Route::get('users/', array('uses' => 'UserController@list'));

Route::any('recommendations', array('uses' => 'RecommendationsController@gag'));

/* LISTS */
Route::group(array('prefix' => 'lists'), function() {

	/* LISTS LIST */
	Route::any('get_lists', array('uses' => 'ListsController@getLists'));

	/* LIST */
	Route::any('add_list', array('uses' => 'ListsController@addList'));
	Route::any('edit_list', array('uses' => 'ListsController@editList'));
	Route::any('remove_list', array('uses' => 'ListsController@removeList'));
	Route::any('get_list', array('uses' => 'ListsController@getList'));

	/* LIST ELEMENT */
	Route::any('add_to_lists', array('uses' => 'ListsController@addToList'));
	Route::any('remove_from_lists', array('uses' => 'ListsController@removeFromList'));

});

// Comments
Route::group(array('prefix' => 'comment'), function() {
	Route::post('add', array('uses' => 'CommentController@add'));
	Route::post('edit', array('uses' => 'CommentController@edit'));
	Route::post('delete', array('uses' => 'CommentController@delete'));
});

// Events
Route::group(array('prefix' => 'events'), function() {
	Route::get('/', array('uses' => 'EventsController@getList'));
});

Route::any('demo', array('uses' => 'DemoController@index'));
