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


Route::get('/', 'HomeController@index');

Route::get('about', array(
	'as' => 'about',
	'uses' => 'HomeController@about'
));

Route::get('icons', array(
	'as' => 'icons',
	'uses' => 'HomeController@icons'
));

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

	// No "base"
	Route::any('books', array('uses' => 'BooksController@show_all'));
		Route::get('books/{id}', array('uses' => 'BooksController@show_item'))->where('id', '[0-9]+'); // только числа
	Route::get('books/random', 	array('uses' => 'RandomController@books'));

	Route::any('films', array('uses' => 'FilmsController@show_all'));
		Route::get('films/{id}', array('uses' => 'FilmsController@show_item'))->where('id', '[0-9]+');

	Route::any('games', array('uses' => 'GamesController@show_all'));
		Route::get('games/{id}', array('uses' => 'GamesController@show_item'))->where('id', '[0-9]+');

	Route::any('albums', array('uses' => 'AlbumsController@show_all'));
		Route::get('albums/{id}', array('uses' => 'AlbumsController@show_item'))->where('id', '[0-9]+');

	Route::get('persons', array('uses' => 'PersonsController@show_all'));
		Route::any('persons/{id}', array('uses' => 'PersonsController@show_item'))->where('id', '[0-9]+');

	Route::get('bands', array('uses' => 'BandsController@show_all'));
		Route::any('bands/{id}', array('uses' => 'BandsController@show_item'))->where('id', '[0-9]+');

	Route::get('companies', array('uses' => 'CompaniesController@show_all'));
		Route::any('companies/{id}', array('uses' => 'CompaniesController@show_item'))->where('id', '[0-9]+');

	Route::group(array('middleware' => 'admin'), function() {
		Route::get('drugs', array('uses' => 'DrugsController@show_all'));
			Route::any('drugs/{id}', array('uses' => 'DrugsController@show_item'))->where('id', '[0-9]+');
	});
	
// Relations
Route::any('{section}/{id}/relations', array('uses' => 'RelationsController@show_item'));
Route::any('{section}/{id}/relations/add', array('uses' => 'RelationsController@add_relation'));

// Years
Route::group(array('prefix' => 'years'), function() {
	Route::any('{section}/{year}', array('uses' => 'YearsController@show_item'))->where('year', '[0-9]+');
});

// genres
Route::group(array('prefix' => 'genres'), function() {
	Route::any('{section}/{id}', array('uses' => 'GenresController@show_item'));
});

// platforms
Route::group(array('prefix' => 'platforms'), function() {
	Route::any('games/{id}', array('uses' => 'PlatformsController@show_item'));
});

// countries
Route::group(array('prefix' => 'countries'), function() {
	Route::any('films/{id}', array('uses' => 'CountriesController@show_item'));
});
	
// collections
Route::group(array('prefix' => 'collections'), function() {
	Route::any('/', array('uses' => 'CollectionsController@show_all'));
	Route::any('{id}', array('uses' => 'CollectionsController@show_item'));
});


// Auth manipulations
Route::group(array('middleware' => 'auth'), function() {
	// Rates
	Route::group(array('prefix' => 'rates'), function () {
		Route::any('rate/{section}/{id}', array('uses' => 'RatesController@rate'));
		Route::get('unrate/{section}/{id}', array('uses' => 'RatesController@unrate'));
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

	Route::get('json', array('uses' => 'SearchController@everything_json'));

	Route::get('person_name', array('uses' => 'SearchController@person_name'));
	Route::get('company_name', array('uses' => 'SearchController@company_name'));
	Route::get('country_name', array('uses' => 'SearchController@country_name'));
	Route::get('collection_name', array('uses' => 'SearchController@collection_name'));
	Route::get('platform_name', array('uses' => 'SearchController@platform_name'));

	Route::get('book_name', array('uses' => 'SearchController@book_name'));
	Route::get('book_genre', array('uses' => 'SearchController@book_genre'));

	Route::get('film_name', array('uses' => 'SearchController@film_name'));
	Route::get('film_genre', array('uses' => 'SearchController@film_genre'));

	Route::get('game_name', array('uses' => 'SearchController@game_name'));
	Route::get('game_genre', array('uses' => 'SearchController@game_genre'));

	Route::get('album_name', array('uses' => 'SearchController@album_name'));
	Route::get('album_genre', array('uses' => 'SearchController@album_genre'));
	Route::get('band_name', array('uses' => 'SearchController@band_name'));

	Route::group(array('prefix' => 'advanced'), function() {
		Route::get('', array('uses' => 'SearchController@advanced'));
		Route::get('persons', array('uses' => 'SearchController@persons'));
		Route::get('companies', array('uses' => 'SearchController@companies'));
		Route::get('bands', array('uses' => 'SearchController@bands'));
		Route::get('collections', array('uses' => 'SearchController@collections'));
		Route::get('{section}/genres', array('uses' => 'SearchController@genres'));
		Route::get('countries', array('uses' => 'SearchController@countries'));
		Route::get('platforms', array('uses' => 'SearchController@platforms'));
		Route::get('{section}/years', array('uses' => 'SearchController@years'));
	});
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
