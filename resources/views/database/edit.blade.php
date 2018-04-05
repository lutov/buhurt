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

	@if('books' == $section)

        <div class="row mt-5">

            <div class="col-md-9">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_book', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'books') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
				<p>{!! Form::text('book_name', $value = $element->name, $attributes = array('placeholder' => 'Название книги', 'id' => 'book_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('book_alt_name', $value = $element->alt_name, $attributes = array('placeholder' => 'Альтернативное или оригинальное название книги', 'id' => 'book_alt_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('book_writer', $value = DatatypeHelper::objectToJsArray($element->writers, '; ', true), $attributes = array('placeholder' => 'Автор', 'class' => 'form-control w-100', 'id' => 'book_writer')) !!}</p>
                <p>{!! Form::text('book_publisher', $value = DatatypeHelper::objectToJsArray($element->publishers, '; ', true), $attributes = array('placeholder' => 'Издатель', 'class' => 'form-control w-100', 'id' => 'book_publisher')) !!}</p>
                <p>{!! Form::textarea('book_description', $value = $element->description, $attributes = array('placeholder' => 'Аннотация', 'class' => 'form-control w-100', 'id' => 'annotation')) !!}</p>
				<p>{!! Form::text('book_genre', $value = DatatypeHelper::collectionToString($element->genres, 'genre', '; ', '', true), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'book_genre')) !!}</p>
				<p>{!! Form::text('book_year', $value = $element->year, $attributes = array('placeholder' => 'Год написания', 'class' => 'form-control w-25')) !!}</p>
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
                            {!! DummyHelper::getExtLink('fantlab', $element->name); !!}
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
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

        <script type="text/javascript" src="/data/js/admin/books.js"></script>

	@endif

	@if('films' == $section)

        <div class="row mt-5">

            <div class="col-md-9">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_film', 'method' => 'POST', 'files' => true)) !!}
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
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
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

            </div>

        </div>

        <script type="text/javascript" src="/data/js/admin/films.js"></script>

	@endif

	@if('games' == $section)

        <div class="row mt-5">

            <div class="col-md-9">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_game', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'games') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
				<p>{!! Form::text('game_name', $value = $element->name, $attributes = array('placeholder' => 'Название игры', 'id' => 'game_name', 'class' => 'form-control w-100')) !!}</p>
				<p>{!! Form::text('game_alt_name', $value = $element->alt_name, $attributes = array('placeholder' => 'Альтернативное или оригинальное название игры', 'id' => 'game_alt_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('game_developer', $value = DatatypeHelper::objectToJsArray($element->developer, '; ', true), $attributes = array('placeholder' => 'Разработчик', 'class' => 'form-control w-100', 'id' => 'game_developer')) !!}</p>
                <p>{!! Form::text('game_publisher', $value = DatatypeHelper::objectToJsArray($element->publisher, '; ', true), $attributes = array('placeholder' => 'Издатель', 'class' => 'form-control w-100', 'id' => 'game_publisher')) !!}</p>
                <p>{!! Form::textarea('game_description', $value = $element->description, $attributes = array('placeholder' => 'Описание', 'class' => 'form-control w-100', 'id' => 'game_description')) !!}</p>
				<p>{!! Form::text('game_genre', $value = DatatypeHelper::collectionToString($element->genres, 'genre', '; ', '', true), $attributes = array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'game_genre')) !!}</p>
				<p>{!! Form::text('game_platform', $value = DatatypeHelper::objectToJsArray($element->platforms, '; ', true), $attributes = array('placeholder' => 'Платформа', 'class' => 'form-control w-100', 'id' => 'game_platform')) !!}</p>
				<p>{!! Form::text('game_year', $value = $element->year, $attributes = array('placeholder' => 'Год выпуска', 'class' => 'form-control w-25')) !!}</p>
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
                    </div>
                </div>

                <div class="card mt-3">
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

        <div class="row mt-5">

            <div class="col-md-9">

			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_album', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'edit') !!}
                {!! Form::hidden('section', $value = 'albums') !!}
                {!! Form::hidden('element_id', $value = $element->id) !!}
				<p>{!! Form::text('album_name', $value = $element->name, $attributes = array('placeholder' => 'Название альбома', 'id' => 'album_name', 'class' => 'form-control w-100')) !!}</p>
                <p>{!! Form::text('album_band', $value = DatatypeHelper::objectToJsArray($element->bands, '; ', true), $attributes = array('placeholder' => 'Авторы и исполнители', 'class' => 'form-control w-100', 'id' => 'album_band')) !!}</p>
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

	@endif

@else
	{!! DummyHelper::regToAdd() !!}
@endif

@stop