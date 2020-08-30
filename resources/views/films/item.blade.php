@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! implode(', ', $element->alt_name) !!}@stop

@section('keywords')фильм, {!! $element->name !!}, {!! implode(', ', $element->alt_name) !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')
	@include('widgets.item', array('schema' => 'Movie'))
@stop
