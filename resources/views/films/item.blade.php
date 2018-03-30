@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! $element->alt_name !!}@stop

@section('keywords')фильм, {!! $element->name !!}, {!! $element->alt_name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Movie">

		<?php // move it to controller obviously
		$info = array(
			'rate' => $rate,
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

		{!! ElementsHelper::getCardHeader($request, $section, $element, $info) !!}

		{!! ElementsHelper::getCardBody($request, $section, $element, $info) !!}

		{!! ElementsHelper::getCardFooter($request, $section, $element, $info) !!}

	</div>

	{!! ElementsHelper::getCardComments($comments) !!}

	{!! ElementsHelper::getCardScripts() !!}
			
@stop