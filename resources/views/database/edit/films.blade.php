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

                {!! Form::open(array('action' => 'Admin\DatabaseController@save', 'class' => 'add_film', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'films') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
                <p>{!! Form::text('film_name', $value = $element->name, $attributes = array('placeholder' => 'Название фильма', 'id' => 'film_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('film_alt_name', $value = $element->alt_name, $attributes = array('placeholder' => 'Альтернативное или оригинальное название фильма', 'id' => 'film_alt_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('film_director', $value = DatatypeHelper::objectToJsArray($element->directors, '; ', true), $attributes = array('placeholder' => 'Режиссер', 'class' => 'form-control w-100', 'id' => 'film_director')) !!}</p>
                <p>{!! Form::text('film_screenwriter', $value = DatatypeHelper::objectToJsArray($element->screenwriters, '; ', true), $attributes = array('placeholder' => 'Сценарист', 'class' => 'form-control w-100', 'id' => 'film_screenwriter')) !!}</p>
                <p>{!! Form::text('film_producer', $value = DatatypeHelper::objectToJsArray($element->producers, '; ', true), $attributes = array('placeholder' => 'Продюсер', 'class' => 'form-control w-100', 'id' => 'film_producer')) !!}</p>
                <p>{!! Form::textarea('film_description', $value = $element->description, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'film_description')) !!}</p>
                <p>{!! Form::text('film_genre', $value = $value = DatatypeHelper::collectionToString($element->genres, 'genre', '; ', '', true), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'film_genre')) !!}</p>
                <p>{!! Form::text('film_country', $value = $value = DatatypeHelper::objectToJsArray($element->countries, '; ', true), $attributes = array('placeholder' => 'Страна производства', 'class' => 'form-control w-100', 'id' => 'film_country')) !!}</p>
                <p>{!! Form::text('film_length', $value = $element->length, $attributes = array('placeholder' => 'Продолжительность', 'class' => 'form-control w-25', 'id' => 'film_length')) !!}</p>
                <p>{!! Form::text('film_year', $value = $element->year, $attributes = array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('film_actors', $value = DatatypeHelper::objectToJsArray($element->actors, '; ', true), $attributes = array('placeholder' => 'Актеры', 'class' => 'form-control w-100', 'id' => 'film_actors')) !!}</p>
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
                            {!! DummyHelper::getExtLink('kinopoisk', $element->name); !!}
                            <?php $wa_query = (!empty($element->alt_name)) ? $element->alt_name : $element->name; echo DummyHelper::getExtLink('worldart', $wa_query); ?>
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex_images', $element->name); !!}
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#films_genres_container" aria-expanded="false" aria-controls="films_genres_container">
                        Жанры фильмов
                    </div>
                    <div class="collapse" id="films_genres_container">
                        {!! DatatypeHelper::objectToList($genres, 'films_genres') !!}
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#countries_container" aria-expanded="false" aria-controls="countries_container">
                        Страны
                    </div>
                    <div class="collapse" id="countries_container">
                        {!! DatatypeHelper::objectToList($countries, 'countries') !!}
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

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#posters" aria-expanded="false" aria-controls="posters">
						<?php
						$poster_name = explode('; ', $element->alt_name)[0];
						$poster_name = str_replace('&', '', $poster_name);
						$poster_name = str_replace(';', '', $poster_name);
						$poster_name = str_replace(':', '', $poster_name);
						$poster_name = str_replace('.', '', $poster_name);
						$poster_name = str_replace(',', '', $poster_name);
						$poster_name = str_replace('  ', ' ', $poster_name);
						$poster_name = str_replace(' ', '-', $poster_name);
						?>
                        <input id="poster_query" class="form-control" onblur="search_poster()" placeholder="Искать постеры" value="{!! $poster_name !!}">
                    </div>
                    <div class="collapse" id="posters"></div>
                </div>

                <div class="card mt-3">

                    <div id="transfer" class="card-body text-center">

                        {!! Form::open(array(
                            'action' => array(
                                'Data\FilmsController@transfer', $element->id),
                                'class' => 'transfer',
                                'method' => 'POST',
                                'files' => false
                            )
                        ) !!}

                        <div>
                            {!! Form::text('recipient_id', $value = '', $attributes = array(
                                'placeholder' => 'Преемник',
                                'id' => 'recipient',
                                'class' => 'form-control'
                            )) !!}
                        </div>

                        <div class="btn-group mt-3">
                            {!! Form::submit('Перенести', $attributes = array(
                                'id' => 'do_transfer',
                                'type' => 'button',
                                'class' => 'btn btn-sm btn-outline-primary'
                            )) !!}
                        </div>

                        {!! Form::close() !!}

                    </div>

                </div>

            </div>

        </div>

        <script type="text/javascript" src="/data/js/admin/films.js"></script>

    @else
        {!! DummyHelper::regToAdd() !!}
    @endif

@stop