@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! $element->alt_name !!}@stop

@section('keywords')альбом, {!! DatatypeHelper::arrayToString($options['bands'], ', ', '/bands/', true) !!}, {!! $element->name !!}, {!! $element->year !!}@stop
@section('description'){!! DatatypeHelper::arrayToString($options['bands'], ', ', '/bands/', true) !!} — {!! $element->name !!} ({!! $element->year !!})@stop

@section('content')

	<div itemscope itemtype="http://schema.org/MusicAlbum">

		{!! ElementsHelper::getCardHeader($request, $section->alt_name, $element, $options) !!}

			<section class="text-center mt-5 mb-3">
				{!! Breadcrumbs::render('element', $element) !!}
			</section>

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}

		{!! ElementsHelper::getCardFooter($request, $section->alt_name, $element, $options) !!}

	</div>

	{!! ElementsHelper::getCardComments($request, $comments, $section->alt_name, $element->id) !!}

	{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}

@stop