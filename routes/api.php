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
Route::get('search', array('uses' => 'Search\SearchController@api'));

/* RANDOM */
Route::get('random/{section}', 	array('uses' => 'Search\RandomController@api'));

/* ELEMENTS */
Route::get('books/{id}', array('uses' => 'Data\BooksController@api'))->where('id', '[0-9]+');
Route::get('films/{id}', array('uses' => 'Data\FilmsController@api'))->where('id', '[0-9]+');
Route::get('games/{id}', array('uses' => 'Data\GamesController@api'))->where('id', '[0-9]+');
Route::get('albums/{id}', array('uses' => 'Data\AlbumsController@api'))->where('id', '[0-9]+');

/* POSTERS */
Route::any('poster', array('uses' => 'Search\PosterController@search'));