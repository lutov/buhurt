@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! DatatypeHelper::arrayToString($element->writers, ', ', '/persons/', false, 'author') !!}@stop

@section('keywords')книга, {!! DatatypeHelper::arrayToString($element->writers, ', ', '/persons/', true) !!}, {!! $element->name !!}, {!! implode(', ', $element->alt_name) !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 25) !!}@stop

@section('content')
    @include('item', array('schema' => 'Book'))
@stop
