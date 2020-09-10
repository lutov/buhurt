@extends('layouts.default')
@section('title'){!! $element->name !!}@stop
@section('subtitle')@stop
@section('keywords'){!! $element->name !!}@if(count($keywords)), {{implode(', ', $keywords)}}@endif @stop
@section('description'){!! $element->name !!}@if(count($keywords)) — {{implode(', ', $keywords)}}@endif @stop
@section('content')
	@include('item.cards.title', array('title' => $element->name, 'subtitle' => ''))
	<div itemscope itemtype="http://schema.org/Person">
		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}
	</div>
	@include('section.tabs')
@stop
