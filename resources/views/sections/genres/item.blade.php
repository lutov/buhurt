@extends('layouts.default')
@section('title'){{$section->name}}@stop
@section('subtitle'){{$element->name}}@stop
@section('content')
    <div itemscope itemtype="http://schema.org/Text">
        <div class="row">
            <div class="@include('card.grid.sidebar') mb-4">
                <div class="mb-4">
                    @include('item.cards.image')
                </div>
                @include('item.cards.description')
            </div>
            <div class="@include('card.grid.main')">
                @include('item.cards.title', array('title' => $element->name, 'subtitle' => ''))
                @include('section.tabs', array('grid' => 'third'))
            </div>
        </div>
    </div>
@stop
