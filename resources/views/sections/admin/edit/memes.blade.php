@php
    /** @var $element */
    $genres = view('collection.names.string', array('collection' => $element->genres, 'delimiter' => '; ', 'no_quotes' => true));
    $collections = view('collection.names.string', array('collection' => $element->collections, 'delimiter' => '; ', 'no_quotes' => true));
@endphp
@extends('layouts.default')
@section('title'){!! $element->name !!}@stop
@section('subtitle') Редактировать @stop
@section('content')
    @if(Auth::check())
        @if(count($errors))
            <div class="row">
                <div class="col-md-12">
                    @foreach ($errors->all() as $error)
                        <p>{!! $error !!}</p>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-9 mb-4"> @include('admin.cards.form') </div>
            <div class="col-md-3 mb-4">
                <div class="card @include('card.class')">
                    <img class="card-img-top" src="{!! ElementsHelper::getCover($section->alt_name, $element->id) !!}" alt="">
                    <div class="card-body text-center">
                        <div class="btn-group">
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                            {!! DummyHelper::getExtLink('wiki_en', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex_images', $element->name); !!}
                        </div>
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#memes_genres_container"
                         aria-expanded="false" aria-controls="memes_genres_container">
                        Жанры мемов
                    </div>
                    <div class="collapse" id="memes_genres_container">
                        @include('collection.names.array', array('id' => 'genres_list', 'collection' => ElementsHelper::getGenres($section->alt_name)))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container"
                         aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        @include('collection.names.array', array('id' => 'collections_list', 'collection' => ElementsHelper::getCollections()))
                    </div>
                    <div id="transfer" class="card-footer text-center">
                        @include('card.transfer', array('controller' => 'MemesController', 'element' => $element))
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="/data/js/admin/memes.js"></script>
    @else
        {!! DummyHelper::regToAdd() !!}
    @endif
@stop
