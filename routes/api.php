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

Route::get('search', array('uses' => 'SearchController@get_list_json'));

Route::get('random/{section}', 	array('uses' => 'RandomController@get_json'));

Route::get('books/{id}', array('uses' => 'BooksController@get_json'))->where('id', '[0-9]+');
Route::get('films/{id}', array('uses' => 'FilmsController@get_json'))->where('id', '[0-9]+');
Route::get('games/{id}', array('uses' => 'GamesController@get_json'))->where('id', '[0-9]+');
Route::get('albums/{id}', array('uses' => 'AlbumsController@get_json'))->where('id', '[0-9]+');

Route::any('poster', array('uses' => 'PosterController@search'));
