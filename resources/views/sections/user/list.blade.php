@extends('layouts.default')
@section('title'){{$ru_section}}@stop
@section('subtitle')@stop
@section('content')
    @include('item.cards.title', array('title' => 'Пользователи', 'subtitle' => ''))
    @include('section.list')
@stop