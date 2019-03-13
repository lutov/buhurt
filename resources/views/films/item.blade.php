@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! $element->alt_name !!}@stop

@section('keywords')фильм, {!! $element->name !!}, {!! $element->alt_name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		{!! Breadcrumbs::render('element', $element) !!}
	</section>

	<div itemscope itemtype="http://schema.org/Movie">

		<?php // move it to controller obviously
		$info = array(
			'rate' => $rate,
			'wanted' => $wanted,
			'not_wanted' => $not_wanted,
			'genres' => $genres,
			'cover' => $cover,
			'similar' => $similar,
			'collections' => $collections,
			'relations' => $relations,
			'countries' => $countries,
			'directors' => $directors,
			'screenwriters' => $screenwriters,
			'producers' => $producers,
			'actors' => $actors,
		);
		?>

		{!! ElementsHelper::getCardHeader($request, $section->alt_name, $element, $info) !!}

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $info) !!}

		{!! ElementsHelper::getCardFooter($request, $section->alt_name, $element, $info) !!}

	</div>

	{!! ElementsHelper::getCardComments($request, $comments, $section->alt_name, $element->id) !!}

	{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}
			
@stop