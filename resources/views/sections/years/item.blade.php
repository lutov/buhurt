@extends('layouts.default')
@section('title'){{$element->name}}@stop
@section('subtitle'){{$section->name}}@stop
@section('keywords'){{$element->name}}, {{$section->name}}@stop
@section('description'){{$section->name}} за {{$element->name}}@stop
@section('content')
    @include('item.cards.title', array('title' => $element->name, 'subtitle' => $section->name))
    @include('section.tabs')
@stop