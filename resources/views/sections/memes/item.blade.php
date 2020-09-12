@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! $element->alt_name !!}@stop

@section('keywords')мем, {!! $element->name !!}, {!! $element->year !!}@stop
@section('description'){!! TextHelper::wordsLimit($element->description, 15) !!}@stop

@section('content')
    @include('item', array('schema' => ''))
@stop