<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 13.03.2019
 * Time: 12:56
 */

// Home
Breadcrumbs::register('home', function($breadcrumbs) {
	$breadcrumbs->push('Главная', route('home'));
});

// Home > About
Breadcrumbs::register('about', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('О сайте', route('about'));
});

// Home > Icons
Breadcrumbs::register('icons', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Иконки', route('icons'));
});

Breadcrumbs::register('section', function($breadcrumbs, $section) {
	$breadcrumbs->parent('home');
	if(isset($section->parent->id)) {
		$breadcrumbs->push($section->parent->name, route($section->parent->alt_name.'_section', $section->parent->id));
	}
	$breadcrumbs->push($section->name, route($section->alt_name, $section->id));
});

Breadcrumbs::register('element', function($breadcrumbs, $element) {

	$class_name = class_basename($element); //dd($class_name);
	$section_name = SectionsHelper::getSectionBy($class_name); //dd($section_name);
	$section = SectionsHelper::getSection($section_name); //dd($section);
	$breadcrumbs->parent('home');
	if(isset($section->parent->id)) {

		$breadcrumbs->push($section->name, route($section->alt_name, $section->id));
		$breadcrumbs->push($section->parent->name, route($section->route, array($section->parent->alt_name, $section->parent->id)));
		$breadcrumbs->push($element->name, route($section->route, array($section->alt_name, $element->id)));

	} else {

		$breadcrumbs->push($section->name, route($section->alt_name, $section->id));
		$breadcrumbs->push($element->name, route($section->route, $element->id));

	}

});