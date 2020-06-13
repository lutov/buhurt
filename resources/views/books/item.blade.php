@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! DatatypeHelper::arrayToString($element->writers, ', ', '/persons/', false, 'author') !!}@stop

@section('keywords')книга, {!! DatatypeHelper::arrayToString($element->writers, ', ', '/persons/', true) !!}, {!! $element->name !!}, {!! implode(', ', $element->alt_name) !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 25) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Book">
		{!! ElementsHelper::getCardHeader($request, $section->alt_name, $element, $options) !!}
		<section class="d-none d-md-block mt-3">
			{!! Breadcrumbs::render('element', $element) !!}
		</section>
		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}
		{!! ElementsHelper::getCardFooter($request, $section->alt_name, $element, $options) !!}
	</div>

	{!! ElementsHelper::getCardComments($request, $comments, $section->alt_name, $element->id) !!}
	{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}

@stop