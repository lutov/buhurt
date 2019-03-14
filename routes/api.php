<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/* SEARCH */
Route::get('search', array('uses' => 'Search\SearchController@getJson'));

/* RANDOM */
Route::get('random/{section}', 	array('uses' => 'Search\RandomController@getJson'));

/* ELEMENTS */
Route::get('books/{id}', array('uses' => 'Data\BooksController@getJson'))->where('id', '[0-9]+');
Route::get('films/{id}', array('uses' => 'Data\FilmsController@getJson'))->where('id', '[0-9]+');
Route::get('games/{id}', array('uses' => 'Data\GamesController@getJson'))->where('id', '[0-9]+');
Route::get('albums/{id}', array('uses' => 'Data\AlbumsController@getJson'))->where('id', '[0-9]+');

/* POSTERS */
Route::any('poster', array('uses' => 'Data\PosterController@search'));