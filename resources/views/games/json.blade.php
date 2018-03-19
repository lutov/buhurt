<?php

use App\Models\Book;
use App\Models\Helpers;

/**
 * @var Game $game
 * @var array $countries
 * @var array $developers
 * @var array $publishers
 * @var array $platforms
 * @var array $collections
 * @var array $genres
 * @var array $rating
 * @var int $cover
 */

header('Content-Type: application/json; charset=UTF-8');

$site = 'https://'.$_SERVER['SERVER_NAME'];

$element = array();

$element['id'] = $game->id;
$element['name'] = $game->name;
$element['alt_name'] = $game->alt_name;
$element['developer'] = Helpers::array2string($developers, ', ', $site.'/companies/');
$element['publisher'] = Helpers::array2string($publishers, ', ', $site.'/companies/');
$element['year'] = $game->year;
$element['genres'] = Helpers::collection2string($genres, 'genre', ', ', $site.'/genres/games/');
$element['platforms'] = Helpers::array2string($platforms, ', ', '/platforms/games/');
$element['cover'] = $site.'/data/img/covers/games/'.$cover.'.jpg';
$element['description'] = $game->description;
$element['rating'] = $rating['average'];
$element['votes'] = Helpers::number($rating['count'], array('голос', 'голоса', 'голосов'));
$element['collections'] = Helpers::collection2string($collections, 'collection', ', ', $site.'/collections/');
$element['link'] = $site.'/games/'.$game->id;

echo json_encode($element);
die();