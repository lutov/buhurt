@php
    /** @var $element */
    $directors = view('collection.names.string', array('collection' => $element->directors, 'delimiter' => '; ', 'no_quotes' => true));
    $screenwriters = view('collection.names.string', array('collection' => $element->screenwriters, 'delimiter' => '; ', 'no_quotes' => true));
    $producers = view('collection.names.string', array('collection' => $element->producers, 'delimiter' => '; ', 'no_quotes' => true));
    $genres = view('collection.names.string', array('collection' => $element->genres, 'delimiter' => '; ', 'no_quotes' => true));
    $countries = view('collection.names.string', array('collection' => $element->countries, 'delimiter' => '; ', 'no_quotes' => true));
    $actors = view('collection.names.string', array('collection' => $element->actors, 'delimiter' => '; ', 'no_quotes' => true));
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
            <div class="col-md-9 mb-4">
                <div class="card @include('card.class')">
                    {!! Form::open(array('action' => 'Admin\DatabaseController@save', 'class' => 'add_film', 'method' => 'POST', 'files' => true)) !!}
                    <div class="card-header">
                        <h1 class="card-title">@yield('title')</h1>
                        <h2 class="card-subtitle mb-2 text-muted">@yield('subtitle')</h2>
                        {!! Form::hidden('action', 'edit') !!}
                        {!! Form::hidden('section', 'films') !!}
                        {!! Form::hidden('element_id', $element->id) !!}
                    </div>
                    <div class="card-body">
                        <p>{!! Form::text('name', $element->name, array('placeholder' => 'Название фильма', 'id' => 'name', 'class' => 'form-control w-100')) !!}</p>
                        <p>{!! Form::text('alt_name', implode('; ', $element->alt_name), array('placeholder' => 'Альтернативное или оригинальное название фильма', 'id' => 'alt_name', 'class' => 'form-control w-100')) !!}</p>
                        <p>{!! Form::text('directors', $directors, array('placeholder' => 'Режиссер', 'class' => 'form-control w-100', 'id' => 'directors')) !!}</p>
                        <p>{!! Form::text('screenwriters', $screenwriters, array('placeholder' => 'Сценарист', 'class' => 'form-control w-100', 'id' => 'screenwriters')) !!}</p>
                        <p>{!! Form::text('producers', $producers, array('placeholder' => 'Продюсер', 'class' => 'form-control w-100', 'id' => 'producers')) !!}</p>
                        <p>{!! Form::textarea('description', $element->description, array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'description')) !!}</p>
                        <p>{!! Form::text('genres', $genres, array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'genres')) !!}</p>
                        <p>{!! Form::text('countries', $countries, array('placeholder' => 'Страна производства', 'class' => 'form-control w-100', 'id' => 'countries')) !!}</p>
                        <p>{!! Form::text('length', $element->length, array('placeholder' => 'Продолжительность', 'class' => 'form-control w-25', 'id' => 'length')) !!}</p>
                        <p>{!! Form::text('year', $element->year, array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                        <p>{!! Form::text('actors', $actors, array('placeholder' => 'Актеры', 'class' => 'form-control w-100', 'id' => 'actors')) !!}</p>
                        <p>{!! Form::text('collections', $collections, array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
                        <b>Обложка</b> {!! Form::file('cover'); !!}
                    </div>
                    <div class="card-footer">
                        {!! Form::submit('Сохранить', array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card @include('card.class')">
                    <img class="card-img-top" src="{!! ElementsHelper::getCover($section, $element->id) !!}" alt="">
                    <div class="card-body text-center">
                        <div class="btn-group">
                            {!! DummyHelper::getExtLink('kinopoisk', $element->name); !!}
                            @php
                                /** @var $element */
                                $wa_query = (isset($element->alt_name[0]) && !empty($element->alt_name[0])) ? $element->alt_name[0] : $element->name;
                            @endphp
                            {!! DummyHelper::getExtLink('worldart', $wa_query); !!}
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex_images', $element->name); !!}
                        </div>
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#genres_list_container"
                         aria-expanded="false" aria-controls="genres_list_container">
                        Жанры фильмов
                    </div>
                    <div class="collapse" id="genres_list_container">
                        @include('collection.names.array', array('id' => 'genres_list', 'collection' => ElementsHelper::getGenres($section)))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#countries_list_container"
                         aria-expanded="false" aria-controls="countries_list_container">
                        Страны
                    </div>
                    <div class="collapse" id="countries_list_container">
                        @include('collection.names.array', array('id' => 'countries_list', 'collection' => ElementsHelper::getCountries()))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container"
                         aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        @include('collection.names.array', array('id' => 'collections_list', 'collection' => ElementsHelper::getCollections()))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#posters" aria-expanded="false"
                         aria-controls="posters">
                        @php
                            /** @var $element */
                            $poster_name = (count($element->alt_name)) ? $element->alt_name[0] : '';
                            $poster_name = str_replace('&', '', $poster_name);
                            $poster_name = str_replace(';', '', $poster_name);
                            $poster_name = str_replace(':', '', $poster_name);
                            $poster_name = str_replace('.', '', $poster_name);
                            $poster_name = str_replace(',', '', $poster_name);
                            $poster_name = str_replace('  ', ' ', $poster_name);
                            $poster_name = str_replace(' ', '-', $poster_name)
                        @endphp
                        <input id="poster_query" class="form-control" onblur="search_poster()"
                               placeholder="Искать постеры" value="{!! $poster_name !!}">
                    </div>
                    <div class="collapse" id="posters"></div>
                    <div id="transfer" class="card-footer text-center">
                        @include('card.transfer', array('controller' => 'FilmsController', 'element' => $element))
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="/data/js/admin/films.js"></script>
    @else
        {!! DummyHelper::regToAdd() !!}
    @endif
@stop
