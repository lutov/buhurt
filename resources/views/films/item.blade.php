@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! implode(', ', $element->alt_name) !!}@stop

@section('keywords')фильм, {!! $element->name !!}, {!! implode(', ', $element->alt_name) !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')

	<div itemscope itemtype="http://schema.org/Movie">
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