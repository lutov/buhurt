<?php
/** @var string $result */
header('Content-Type: application/json; charset=UTF-8');

$site = 'https://'.$_SERVER['SERVER_NAME'];

foreach($result as $key => $value) {
	
	foreach($value as $el_key => $element) {
		
		$result[$key][$el_key]['link'] = $site.'/'.$key.'/'.$element['id'];
		
	}
	
}

echo json_encode($result);
die();