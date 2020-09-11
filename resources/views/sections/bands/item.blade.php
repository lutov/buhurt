@extends('layouts.default')
@section('title'){!! $element->name !!}@stop
@section('subtitle')@stop
@section('keywords')группа, {!! $element->name !!}, альбомы@stop
@section('description')Группа {!! $element->name !!}@stop
@section('content')
    @include('item.cards.title', array('title' => $element->name))
    <div itemscope itemtype="http://schema.org/MusicGroup">
        @include('item.body')
    </div>
    @include('section.tabs')
@stop
