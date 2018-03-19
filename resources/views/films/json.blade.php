<?php

use App\Models\Book;
use App\Models\Helpers;

/** @var Film $film */
/** @var array $countries */
/** @var array $directors */
/** @var array $screenwriters */
/** @var array $producers */
/** @var array $actors */
/** @var array $collections */
/** @var array $genres */
/** @var array $rating */
/** @var int $cover */

header('Content-Type: application/json; charset=UTF-8');

$site = 'https://'.$_SERVER['SERVER_NAME'];

$element = array();

$element['id'] = $film->id;
$element['name'] = $film->name;
$element['alt_name'] = $film->alt_name;
$element['country'] = Helpers::array2string($countries, ', ', $site.'/countries/films/');
$element['director'] = Helpers::array2string($directors, ', ', $site.'/persons/');
$element['screenwriter'] = Helpers::array2string($screenwriters, ', ', $site.'/persons/');
$element['producer'] = Helpers::array2string($producers, ', ', $site.'/persons/');
$element['actors'] = Helpers::array2string($actors, ', ', $site.'/persons/');
$element['year'] = $film->year;
$element['length'] = $film->length;
$element['genres'] = Helpers::collection2string($genres, 'genre', ', ', $site.'/genres/films/');
$element['cover'] = $site.'/data/img/covers/films/'.$cover.'.jpg';
$element['description'] = $film->description;
$element['rating'] = $rating['average'];
$element['votes'] = Helpers::number($rating['count'], array('голос', 'голоса', 'голосов'));
$element['collections'] = Helpers::collection2string($collections, 'collection', ', ', $site.'/collections/');
$element['link'] = $site.'/films/'.$film->id;

echo json_encode($element);
die();