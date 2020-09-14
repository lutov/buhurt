@extends('layouts.default')
@section('title')Жанры@stop
@section('subtitle')@stop
@section('content')
    @include('item.cards.title', array('title' => 'Жанры', 'subtitle' => ''))
    @include('section.tabs')
@stop
