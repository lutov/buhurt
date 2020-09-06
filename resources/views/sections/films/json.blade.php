<?php

header('Content-Type: application/json; charset=UTF-8');

$site = 'https://'.$_SERVER['SERVER_NAME'];

$output = array();

$output['id'] = $element->id;
$output['name'] = $element->name;
$output['alt_name'] = $element->alt_name;
$output['country'] = DatatypeHelper::arrayToString($element->countries, ', ', $site.'/countries/films/');
$output['director'] = DatatypeHelper::arrayToString($element->directors, ', ', $site.'/persons/');
$output['screenwriter'] = DatatypeHelper::arrayToString($element->screenwriters, ', ', $site.'/persons/');
$output['producer'] = DatatypeHelper::arrayToString($element->producers, ', ', $site.'/persons/');
$output['actors'] = DatatypeHelper::arrayToString($element->actors, ', ', $site.'/persons/');
$output['year'] = $element->year;
$output['length'] = $element->length;
$output['genres'] = DatatypeHelper::collectionToString($element->genres, 'genre', ', ', $site.'/genres/films/');
$output['cover'] = $site.ElementsHelper::getCover($section->alt_name, $element->id);
$output['description'] = $element->description;
$output['rating'] = $rating['average'];
$output['votes'] = TextHelper::number(ElementsHelper::countRating($element)['count'], array('голос', 'голоса', 'голосов'));
$output['collections'] = DatatypeHelper::collectionToString($element->collections, 'collection', ', ', $site.'/collections/');
$output['link'] = $site.'/films/'.$element->id;

echo json_encode($output);
die();
