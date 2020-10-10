@php
    /** @var $element */
    $developers = view('collection.names.string', array('collection' => $element->developers, 'delimiter' => '; ', 'no_quotes' => true));
    $games_publishers = view('collection.names.string', array('collection' => $element->games_publishers, 'delimiter' => '; ', 'no_quotes' => true));
    $platforms = view('collection.names.string', array('collection' => $element->platforms, 'delimiter' => '; ', 'no_quotes' => true));
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
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex_images', $element->name); !!}
                        </div>
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#games_genres_container"
                         aria-expanded="false" aria-controls="games_genres_container">
                        Жанры игр
                    </div>
                    <div class="collapse" id="games_genres_container">
                        @include('collection.names.array', array('id' => 'genres_list', 'collection' => ElementsHelper::getGenres($section->alt_name)))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#platforms_container"
                         aria-expanded="false" aria-controls="platforms_container">
                        Платформы
                    </div>
                    <div class="collapse" id="platforms_container">
                        @include('collection.names.array', array('id' => 'platforms_list', 'collection' => ElementsHelper::getPlatforms()))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container"
                         aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        @include('collection.names.array', array('id' => 'collections_list', 'collection' => ElementsHelper::getCollections()))
                    </div>
                    <div id="transfer" class="card-footer text-center">
                        @include('card.transfer', array('controller' => 'GamesController', 'element' => $element))
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="/data/js/admin/games.js"></script>
    @else
        {!! DummyHelper::regToAdd() !!}
    @endif
@stop
