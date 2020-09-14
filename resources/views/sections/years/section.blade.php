@extends('layouts.default')
@section('title'){{$section->name}}@stop
@section('subtitle')@stop
@section('content')
    @include('item.cards.title', array('title' => 'Календарь', 'subtitle' => ''))
    @include('section.tabs.lists')
@stop
