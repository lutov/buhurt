@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! $element->alt_name !!}@stop

@section('keywords')альбом, {!! $element->name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/MusicAlbum">

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
			'bands' => $bands,
			'tracks' => $tracks,
		);
		?>

		{!! ElementsHelper::getCardHeader($request, $section->alt_name, $element, $info) !!}

			<section class="text-center mt-5 mb-3">
				{!! Breadcrumbs::render('element', $element) !!}
			</section>

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $info) !!}

		{!! ElementsHelper::getCardFooter($request, $section->alt_name, $element, $info) !!}

	</div>

	{!! ElementsHelper::getCardComments($request, $comments, $section->alt_name, $element->id) !!}

	{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}

@stop