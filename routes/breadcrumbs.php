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
	$breadcrumbs->push($section->name, route($section->alt_name, $section->id));
});

Breadcrumbs::register('element', function($breadcrumbs, $element) {
	$breadcrumbs->parent($element->section);
	$breadcrumbs->push($element->name, route($element->section, $element->id));
});