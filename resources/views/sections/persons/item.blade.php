@extends('layouts.default')
@section('title'){!! $element->name !!}@stop
@section('subtitle')@stop
@section('keywords'){!! $element->name !!}@if(count($keywords)), {{implode(', ', $keywords)}}@endif @stop
@section('description'){!! $element->name !!}@if(count($keywords)) — {{implode(', ', $keywords)}}@endif @stop
@section('content')
    <div itemscope itemtype="http://schema.org/Person">
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
