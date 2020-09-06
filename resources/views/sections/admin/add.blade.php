@extends('layouts.default')

@section('title')Добавить элемент@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center">
        <h1 class="pt-5">@yield('title')</h1>
        <h2 class="pb-3">@yield('subtitle')</h2>
        <ul class="list-inline">

            @if(Auth::check())
                <li class="list-inline-item"><a href="/admin/add/books">Книгу</a></li>
                <li class="list-inline-item"><a href="/admin/add/films">Фильм</a></li>
                <li class="list-inline-item"><a href="/admin/add/games">Игру</a></li>
                <li class="list-inline-item"><a href="/admin/add/albums">Альбом</a></li>
            @endif

        </ul>
    </section>

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

    @if('books' == $section)

        <div class="row">

            <div class="col-md-9 mb-4">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_book', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'books') !!}
				<p>{!! Form::text('book_name', $value = Input::get('book_name', ''), $attributes = array('placeholder' => 'Название книги', 'id' => 'book_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('book_alt_name', $value = Input::get('book_alt_name', ''), $attributes = array('placeholder' => 'Альтернативное или оригинальное название книги', 'id' => 'book_alt_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('book_writer', $value = Input::get('book_writer', ''), $attributes = array('placeholder' => 'Автор', 'class' => 'form-control w-100', 'id' => 'book_writer')) !!}</p>
                <p>{!! Form::text('book_publisher', $value = Input::get('book_publisher', ''), $attributes = array('placeholder' => 'Издатель', 'class' => 'form-control w-100', 'id' => 'book_publisher')) !!}</p>
                <p>{!! Form::textarea('book_description', $value = null, $attributes = array('placeholder' => 'Аннотация', 'class' => 'form-control w-100', 'id' => 'annotation')) !!}</p>
				<p>{!! Form::text('book_genre', $value = Input::get('book_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'book_genre')) !!}</p>
				<p>{!! Form::text('book_year', $value = Input::get('book_year', ''), $attributes = array('placeholder' => 'Год написания', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save', 'class' => 'btn btn-secondary', 'role' => 'button' )) !!}
			{!! Form::close() !!}

            </div>

            <div class="col-md-3 mb-4">

                <div class="card">
                    <div class="card-header" data-toggle="collapse" data-target="#books_genres_container" aria-expanded="false" aria-controls="books_genres_container">
                        Жанры книг
                    </div>
                    <div class="collapse" id="books_genres_container">
                        {!! DatatypeHelper::objectToList($genres, 'books_genres') !!}
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

    @endif

    @if('films' == $section)

        <div class="row">

            <div class="col-md-9 mb-4">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_film', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'films') !!}
				<p>{!! Form::text('film_name', $value = Input::get('film_name', ''), $attributes = array('placeholder' => 'Название фильма', 'id' => 'film_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('film_alt_name', $value = Input::get('film_alt_name', ''), $attributes = array('placeholder' => 'Альтернативное или оригинальное название фильма', 'id' => 'film_alt_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('film_director', $value = Input::get('film_director', ''), $attributes = array('placeholder' => 'Режиссер', 'class' => 'form-control w-100', 'id' => 'film_director')) !!}</p>
				<p>{!! Form::text('film_screenwriter', $value = Input::get('film_screenwriter', ''), $attributes = array('placeholder' => 'Сценарист', 'class' => 'form-control w-100', 'id' => 'film_screenwriter')) !!}</p>
                <p>{!! Form::text('film_producer', $value = Input::get('film_producer', ''), $attributes = array('placeholder' => 'Продюсер', 'class' => 'form-control w-100', 'id' => 'film_producer')) !!}</p>
                <p>{!! Form::textarea('film_description', $value = null, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'film_description')) !!}</p>
				<p>{!! Form::text('film_genre', $value = Input::get('film_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'film_genre')) !!}</p>
				<p>{!! Form::text('film_country', $value = Input::get('film_country', ''), $attributes = array('placeholder' => 'Страна производства', 'class' => 'form-control w-100', 'id' => 'film_country')) !!}</p>
				<p>{!! Form::text('film_length', $value = Input::get('film_length', ''), $attributes = array('placeholder' => 'Продолжительность', 'class' => 'form-control w-25', 'id' => 'film_length')) !!}</p>
				<p>{!! Form::text('film_year', $value = Input::get('film_year', ''), $attributes = array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('film_actors', $value = Input::get('film_actors', ''), $attributes = array('placeholder' => 'Актеры', 'class' => 'form-control w-100', 'id' => 'film_actors')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save', 'class' => 'btn btn-secondary', 'role' => 'button' )) !!}
			{!! Form::close() !!}

            </div>

            <div class="col-md-3 mb-4">

                <div class="card">
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

            </div>

        </div>

        <script type="text/javascript" src="/data/js/admin/films.js"></script>

    @endif

    @if('games' == $section)

        <div class="row">

            <div class="col-md-9 mb-4">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_game', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'games') !!}
				<p>{!! Form::text('game_name', $value = Input::get('game_name', ''), $attributes = array('placeholder' => 'Название игры', 'id' => 'game_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('game_alt_name', $value = Input::get('game_alt_name', ''), $attributes = array('placeholder' => 'Альтернативное или оригинальное название игры', 'id' => 'game_alt_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('game_developer', $value = Input::get('game_developer', ''), $attributes = array('placeholder' => 'Разработчик', 'class' => 'form-control w-100', 'id' => 'game_developer')) !!}</p>
                <p>{!! Form::text('game_publisher', $value = Input::get('game_publisher', ''), $attributes = array('placeholder' => 'Издатель', 'class' => 'form-control w-100', 'id' => 'game_publisher')) !!}</p>
                <p>{!! Form::textarea('game_description', $value = null, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'game_description')) !!}</p>
				<p>{!! Form::text('game_genre', $value = Input::get('game_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'game_genre')) !!}</p>
				<p>{!! Form::text('game_platform', $value = Input::get('game_platform', ''), $attributes = array('placeholder' => 'Платформа', 'class' => 'form-control w-100', 'id' => 'game_platform')) !!}</p>
				<p>{!! Form::text('game_year', $value = Input::get('game_year', ''), $attributes = array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save', 'class' => 'btn btn-secondary', 'role' => 'button' )) !!}
			{!! Form::close() !!}

            </div>

            <div class="col-md-3 mb-4">

                <div class="card">
                    <div class="card-header" data-toggle="collapse" data-target="#games_genres_container" aria-expanded="false" aria-controls="games_genres_container">
                        Жанры игр
                    </div>
                    <div class="collapse" id="games_genres_container">
                        {!! DatatypeHelper::objectToList($genres, 'games_genres') !!}
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" data-toggle="collapse" data-target="#platforms_container" aria-expanded="false" aria-controls="platforms_container">
                        Платформы
                    </div>
                    <div class="collapse" id="platforms_container">
                        {!! DatatypeHelper::objectToList($platforms, 'platforms') !!}
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

        <script type="text/javascript" src="/data/js/admin/games.js"></script>

    @endif

    @if('albums' == $section)

        <div class="row">

            <div class="col-md-9 mb-4">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_album', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'albums') !!}
				<p>{!! Form::text('album_name', $value = Input::get('album_name', ''), $attributes = array('placeholder' => 'Название альбома', 'id' => 'album_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('album_band', $value = Input::get('album_band', ''), $attributes = array('placeholder' => 'Авторы и исполнители', 'class' => 'form-control w-100', 'id' => 'album_band')) !!}</p>

                <ol id="tracks">
					<?php
					    $tracks = '';
					    for($i = 0; $i < 9; $i++) {$tracks .= '<li><input type="text" class="form-control w-100 mb-3" name="tracks[]" placeholder="Трек" /></li>';}
					    echo $tracks;
					?>
                </ol>
                <p><input type="button" class="btn btn-secondary" value="Добавить трек" onclick="add_track()"></p>

                <p>{!! Form::textarea('album_description', $value = null, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'album_description')) !!}</p>
				<p>{!! Form::text('album_genre', $value = Input::get('album_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'album_genre')) !!}</p>
				<p>{!! Form::text('album_year', $value = Input::get('album_year', ''), $attributes = array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save', 'class' => 'btn btn-secondary', 'role' => 'button' )) !!}
			{!! Form::close() !!}

            </div>

            <div class="col-md-3 mb-4">

                <div class="card">
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

    @endif

@else
	{!! DummyHelper::regToAdd() !!}
@endif

@stop