@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! DatatypeHelper::arrayToString($writers, ', ', '/persons/', false, 'author') !!}@stop

@section('keywords')книга, {!! $element->name !!}, {!! $element->alt_name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		{!! Breadcrumbs::render('element', $element) !!}
	</section>

	<div itemscope itemtype="http://schema.org/Book">

		<?php
			$info = array(
				'rate' => $rate,
				'wanted' => $wanted,
				'not_wanted' => $not_wanted,
				'genres' => $genres,
				'cover' => $cover,
				'similar' => $similar,
				'collections' => $collections,
				'relations' => $relations,
				'writers' => $writers,
				'publishers' => $publishers,
			);
		?>

		{!! ElementsHelper::getCardHeader($request, $section->alt_name, $element, $info) !!}

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $info) !!}

		{!! ElementsHelper::getCardFooter($request, $section->alt_name, $element, $info) !!}

	</div>

	{!! ElementsHelper::getCardComments($request, $comments, $section->alt_name, $element->id) !!}

	{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}

@stop