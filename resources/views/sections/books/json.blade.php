<?php

use App\Models\Book;
use App\Models\Helpers;

/** @var Book $book */
/** @var array $writers */
/** @var array $publishers */
/** @var array $collections */
/** @var array $genres */
/** @var array $rating */
/** @var int $cover */

header('Content-Type: application/json; charset=UTF-8');

$site = 'https://'.$_SERVER['SERVER_NAME'];

$element = array();

$element['id'] = $book->id;
$element['name'] = $book->name;
$element['alt_name'] = $book->alt_name;
$element['author'] = Helpers::array2string($writers, ', ', $site.'/persons/');
$element['publishers'] = Helpers::array2string($publishers, ', ', $site.'/companies/');
$element['year'] = $book->year;
$element['genres'] = Helpers::collection2string($genres, 'genre', ', ', $site.'/genres/books/');
$element['cover'] = $site.'/data/img/covers/books/'.$cover.'.jpg';
$element['description'] = $book->description;
$element['rating'] = $rating['average'];
$element['votes'] = Helpers::number($rating['count'], array('голос', 'голоса', 'голосов'));
$element['collections'] = Helpers::collection2string($collections, 'collection', ', ', $site.'/collections/');
$element['link'] = $site.'/books/'.$book->id;

echo json_encode($element);
die();