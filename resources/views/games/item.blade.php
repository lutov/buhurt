@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! $element->alt_name !!}@stop

@section('keywords')игра, {!! $element->name !!}, {!! $element->alt_name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Game">

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
			'game_platforms' => $platforms,
			'game_developers' => $developers,
			'game_publishers' => $publishers,
		);
		?>

		{!! ElementsHelper::getCardHeader($request, $section, $element, $info) !!}

		{!! ElementsHelper::getCardBody($request, $section, $element, $info) !!}

		{!! ElementsHelper::getCardFooter($request, $section, $element, $info) !!}

	</div>

	{!! ElementsHelper::getCardComments($request, $comments, $section, $element->id) !!}

	{!! ElementsHelper::getCardScripts($section, $element->id) !!}

@stop