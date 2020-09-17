@extends('layouts.default')
@section('title'){{$section->name}}@stop
@section('subtitle')@stop
@section('content')
    @include('section.cards.header')
    @include('section.events')
    @include('section.cards.footer')
@stop
