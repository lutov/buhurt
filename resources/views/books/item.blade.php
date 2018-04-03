@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! DatatypeHelper::arrayToString($writers, ', ', '/persons/', false, 'author') !!}@stop

@section('keywords')книга, {!! $element->name !!}, {!! $element->alt_name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

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

		{!! ElementsHelper::getCardHeader($request, $section, $element, $info) !!}

		{!! ElementsHelper::getCardBody($request, $section, $element, $info) !!}

		{!! ElementsHelper::getCardFooter($request, $section, $element, $info) !!}

	</div>

	{!! ElementsHelper::getCardComments($comments, $section, $element->id) !!}

	{!! ElementsHelper::getCardScripts($section, $element->id) !!}

@stop