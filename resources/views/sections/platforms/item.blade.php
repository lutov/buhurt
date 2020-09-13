@extends('layouts.default')
@section('title'){{$element->name}}@stop
@section('subtitle')@stop
@section('content')
    @include('item.cards.title', array('title' => $element->name, 'subtitle' => ''))
    <div itemscope itemtype="http://schema.org/Product">
        @include('item.body')
    </div>
    @include('section.tabs')
@stop
