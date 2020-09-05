@php
    /** @var $element */
    $bands = view('widgets.string-collection-names', array('collection' => $element->bands, 'delimiter' => '; ', 'no_quotes' => true));
    $genres = view('widgets.string-collection-names', array('collection' => $element->genres, 'delimiter' => '; ', 'no_quotes' => true));
    $collections = view('widgets.string-collection-names', array('collection' => $element->collections, 'delimiter' => '; ', 'no_quotes' => true));
@endphp
@extends('layouts.default')
@section('title'){!! $element->name !!}@stop
@section('subtitle') Редактировать @stop
@section('content')
    @if(Auth::check())
        @if(count($errors))
            <div class="row mt-5">
                <div class="col-md-12">
                    @foreach ($errors->all() as $error)
                        <p>{!! $error !!}</p>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="row mt-5">
            <div class="col-md-9">
                <div class="card @include('widgets.card-class')">
                    {!! Form::open(array('action' => 'Admin\DatabaseController@save', 'class' => 'add_album', 'method' => 'POST', 'files' => true)) !!}
                    <div class="card-header">
                        <h1 class="card-title">@yield('title')</h1>
                        <h2 class="card-subtitle mb-2 text-muted">@yield('subtitle')</h2>
                        {!! Form::hidden('action', 'edit') !!}
                        {!! Form::hidden('section', 'albums') !!}
                        {!! Form::hidden('element_id', $element->id) !!}
                    </div>
                    <div class="card-body">
                        <p>{!! Form::text('name', $element->name, array('placeholder' => 'Название альбома', 'id' => 'name', 'class' => 'form-control w-100')) !!}</p>
                        <p>{!! Form::text('bands', $bands, array('placeholder' => 'Авторы и исполнители', 'class' => 'form-control w-100', 'id' => 'bands')) !!}</p>
                        <ol id="tracks">
                            @php
                                /** @var $element */
                                $track_list = $element->tracks()->orderBy('order')->get();
                            @endphp
                            @foreach($track_list as $key => $track)
                                <li>
                                    <input type="text" class="form-control w-100 mb-3" name="tracks[]" placeholder="Трек" value="{!! $track->name !!}" />
                                </li>
                            @endforeach
                        </ol>
                        <p><input type="button" class="btn btn-secondary" value="Добавить трек" onclick="add_track()"></p>
                        <p>{!! Form::textarea('description', $element->description, array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'description')) !!}</p>
                        <p>{!! Form::text('genres', $genres, array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'genres')) !!}</p>
                        <p>{!! Form::text('year', $element->year, array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                        <p>{!! Form::text('collections', $collections, array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
                        <b>Обложка</b> {!! Form::file('cover'); !!}
                    </div>
                    <div class="card-footer">
                        {!! Form::submit('Сохранить', array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="card @include('widgets.card-class')">
                    <img class="card-img-top" src="{!! ElementsHelper::getCover($section, $element->id) !!}" alt="">
                    <div class="card-body text-center">
                        <div class="btn-group">
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                            {!! DummyHelper::getExtLink('wiki_en', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex_music', $element->name); !!}
                            {!! DummyHelper::getExtLink('discogs', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex_images_square', $element->name); !!}
                        </div>
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#albums_genres_container" aria-expanded="false" aria-controls="albums_genres_container">
                        Жанры музыки
                    </div>
                    <div class="collapse" id="albums_genres_container">
                        @include('widgets.list-collection-names', array('id' => 'genres_list', 'collection' => ElementsHelper::getGenres($section)))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container" aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        @include('widgets.list-collection-names', array('id' => 'collections_list', 'collection' => ElementsHelper::getCollections()))
                    </div>
                    <div id="transfer" class="card-footer text-center">
                        @include('widgets.card-transfer', array('controller' => 'AlbumsController', 'element' => $element))
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="/data/js/admin/albums.js"></script>
    @else
        {!! DummyHelper::regToAdd() !!}
    @endif
@stop
