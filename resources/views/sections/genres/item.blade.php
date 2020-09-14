@extends('layouts.default')
@section('title'){{$section->name}}@stop
@section('subtitle'){{$element->name}}@stop
@section('content')
    @include('item.cards.title', array('title' => $element->name, 'subtitle' => ''))
    <div itemscope itemtype="http://schema.org/Person">
        @include('item.body')
    </div>
    @include('section.tabs')
@stop
