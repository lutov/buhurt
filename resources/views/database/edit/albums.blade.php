@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')Редактировать элемент@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

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

                {!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_album', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'albums') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
                <p>{!! Form::text('album_name', $value = $element->name, $attributes = array('placeholder' => 'Название альбома', 'id' => 'album_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('album_band', $value = DatatypeHelper::objectToJsArray($element->bands, '; ', true), $attributes = array('placeholder' => 'Авторы и исполнители', 'class' => 'form-control w-100', 'id' => 'album_band')) !!}</p>

                <ol id="tracks">
					<?php

					//echo DebugHelper::dump($element->tracks(), 1);
					//echo '<pre>'.print_r($element->tracks, 1).'</pre>';

					$track_list = $element->tracks()->orderBy('order')->get();
					$tracks = '';
					foreach($track_list as $key => $value) {

						$tracks .= '<li><input type="text" class="form-control w-100 mb-3" name="tracks[]" placeholder="Трек" value="'.$value->name.'" /></li>';

					}
					echo $tracks;

					?>
                </ol>
                <p><input type="button" class="btn btn-secondary" value="Добавить трек" onclick="add_track()"></p>

                <p>{!! Form::textarea('album_description', $value = $element->description, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'album_description')) !!}</p>
                <p>{!! Form::text('album_genre', $value = DatatypeHelper::collectionToString($element->genres, 'genre', '; ', '', true), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'album_genre')) !!}</p>
                <p>{!! Form::text('album_year', $value = $element->year, $attributes = array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('collections', $value = DatatypeHelper::collectionToString($element->collections, 'collection', '; ', '', true), $attributes = array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
                <p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
                {!! Form::submit('Сохранить', $attributes = array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                {!! Form::close() !!}

            </div>

            <div class="col-md-3">

                <div class="card">
                    <img class="card-img-top" src="/data/img/covers/{!! $section !!}/{!! $element_cover !!}.jpg" alt="">
                    <div class="card-body text-center">
                        <p class="card-text">Дополнительная информация</p>
                        <div class="btn-group">
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                            {!! DummyHelper::getExtLink('wiki_en', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex_music', $element->name); !!}
                            {!! DummyHelper::getExtLink('discogs', $element->name); !!}
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#albums_genres_container" aria-expanded="false" aria-controls="albums_genres_container">
                        Жанры музыки
                    </div>
                    <div class="collapse" id="albums_genres_container">
                        {!! DatatypeHelper::objectToList($genres, 'albums_genres') !!}
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container" aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        {!! DatatypeHelper::objectToList($collections, 'collections_list') !!}
                    </div>
                </div>

            </div>

        </div>

        <script type="text/javascript" src="/data/js/admin/albums.js"></script>

    @else
        {!! DummyHelper::regToAdd() !!}
    @endif

@stop