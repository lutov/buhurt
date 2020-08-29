@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! implode(', ', $element->alt_name) !!}@stop

@section('keywords')фильм, {!! $element->name !!}, {!! implode(', ', $element->alt_name) !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')
	{!! Breadcrumbs::render('element', $element) !!}
	<div itemscope itemtype="http://schema.org/Movie">
		{!! ElementsHelper::getCardHeader($request, $section->alt_name, $element, $options) !!}
		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}
		{!! ElementsHelper::getCardFooter($request, $section->alt_name, $element, $options) !!}
	</div>
	{!! ElementsHelper::getCardComments($request, $comments, $section->alt_name, $element->id) !!}
	{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}
@stop
