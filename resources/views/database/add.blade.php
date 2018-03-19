@extends('layouts.default')

@section('title')
	Добавить элемент
@stop

@section('subtitle')

@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

@if(Auth::check())
	<p>
		<span @if('books' != $section) class="symlink" @endif id="books" onclick="show_section('books');">Книгу</span>
		|
		<span @if('films' != $section) class="symlink" @endif id="films" onclick="show_section('films');">Фильм</span>
		|
		<span @if('games' != $section) class="symlink" @endif id="games" onclick="show_section('games');">Игру</span>
        |
		<span @if('albums' != $section) class="symlink" @endif id="albums" onclick="show_section('albums');">Альбом</span>
	</p>

	@if(count($errors))
	<div class="error">
		@foreach ($errors->all() as $error)
			<p>{!! $error !!}</p>
		@endforeach
    </div>
    @endif

    @if('books' == $section)
	<div id="add_book" class="visible">
		<div class="half">
			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_book', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'books') !!}
				<p>{!! Form::text('book_name', $value = Input::get('book_name', ''), $attributes = array('placeholder' => 'Название книги', 'id' => 'book_name', 'class' => 'full')) !!}</p>
				<p>{!! Form::text('book_alt_name', $value = Input::get('book_alt_name', ''), $attributes = array('placeholder' => 'Альтернативное или оригинальное название книги', 'id' => 'book_alt_name', 'class' => 'full')) !!}</p>
				<p>{!! Form::text('book_writer', $value = Input::get('book_writer', ''), $attributes = array('placeholder' => 'Автор', 'class' => 'full', 'id' => 'book_writer')) !!}</p>
                <p>{!! Form::text('book_publisher', $value = Input::get('book_publisher', ''), $attributes = array('placeholder' => 'Издатель', 'class' => 'full', 'id' => 'book_publisher')) !!}</p>
                <p>{!! Form::textarea('book_description', $value = null, $attributes = array('placeholder' => 'Аннотация', 'class' => 'full', 'id' => 'annotation')) !!}</p>
				<p>{!! Form::text('book_genre', $value = Input::get('book_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'full', 'id' => 'book_genre')) !!}</p>
				<p>{!! Form::text('book_year', $value = Input::get('book_year', ''), $attributes = array('placeholder' => 'Год написания', 'class' => 'third')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'full', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save')) !!}
			{!! Form::close() !!}
		</div><!--
		--><div class="side_note">
			<!--p>Рекомендации к правильному заполнению:</p>
			<ul>
                <li>Если название книги появится в предлагаемых вариантах, чтобы отличить вашу укажите в скобках автора или год</li>
                <li>Элементы разделяйте точкой с запятой&nbsp;—&nbsp;в названиях жанров могут быть запятые, так что, они не подходят</li>
                <li>Рассказы отдельными элементами не нужны. Добавляйте изданные книги целиком, указывая всех авторов</li>
                <li>Разные издания одной книги не нужны</li>
                <li>В сборниках могут быть пересечения с уже добавленными элементами. Это не страшно, если только они не копируют друг друга полностью</li>
			</ul-->
			<p><span class="symlink" onclick="expand_genres(this, 'books_genres')">Жанры книг</span></p>
			{!! Helpers::object2list($genres, 'books_genres') !!}

            <p><span class="symlink" onclick="expand_genres(this, 'collections_list')">Коллекции</span></p>
            {!! Helpers::object2list($collections, 'collections_list') !!}
		</div>
	</div>

    <script>

        $(document).ready(function() {

            var writer = $('#book_writer');
            var publisher = $('#book_publisher');
            var collections = $('#collections');
            // book
            $('#book_name').autocomplete({
                source: "{!! URL::action('SearchController@book_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            $('#book_genre').autocomplete({
                source: "{!! URL::action('SearchController@book_genre') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            writer.autocomplete({
                source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            publisher.autocomplete({
                source: "{!! URL::action('SearchController@company_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            collections.autocomplete({
                source: "{!! URL::action('SearchController@collection_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            bind_genres('books_genres', 'book_genre');
            bind_genres('collections_list', 'collections');

            writer.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });
            publisher.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });
            collections.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });

        });

    </script>
    @endif

    @if('films' == $section)
	<div id="add_film" class="visible">
	 	<div class="half">
			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_film', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'films') !!}
				<p>{!! Form::text('film_name', $value = Input::get('film_name', ''), $attributes = array('placeholder' => 'Название фильма', 'id' => 'film_name', 'class' => 'full')) !!}</p>
				<p>{!! Form::text('film_alt_name', $value = Input::get('film_alt_name', ''), $attributes = array('placeholder' => 'Альтернативное или оригинальное название фильма', 'id' => 'film_alt_name', 'class' => 'full')) !!}</p>
				<p>{!! Form::text('film_director', $value = Input::get('film_director', ''), $attributes = array('placeholder' => 'Режиссер', 'class' => 'full', 'id' => 'film_director')) !!}</p>
				<p>{!! Form::text('film_screenwriter', $value = Input::get('film_screenwriter', ''), $attributes = array('placeholder' => 'Сценарист', 'class' => 'full', 'id' => 'film_screenwriter')) !!}</p>
                <p>{!! Form::text('film_producer', $value = Input::get('film_producer', ''), $attributes = array('placeholder' => 'Продюсер', 'class' => 'full', 'id' => 'film_producer')) !!}</p>
                <p>{!! Form::textarea('film_description', $value = null, $attributes = array('placeholder' => 'Описание', 'class' => 'full', 'id' => 'film_description')) !!}</p>
				<p>{!! Form::text('film_genre', $value = Input::get('film_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'full', 'id' => 'film_genre')) !!}</p>
				<p>{!! Form::text('film_country', $value = Input::get('film_country', ''), $attributes = array('placeholder' => 'Страна производства', 'class' => 'full', 'id' => 'film_country')) !!}</p>
				<p>{!! Form::text('film_length', $value = Input::get('film_length', ''), $attributes = array('placeholder' => 'Продолжительность', 'class' => 'third', 'id' => 'film_length')) !!}</p>
				<p>{!! Form::text('film_year', $value = Input::get('film_year', ''), $attributes = array('placeholder' => 'Год выпуска', 'class' => 'third')) !!}</p>
                <p>{!! Form::text('film_actors', $value = Input::get('film_actors', ''), $attributes = array('placeholder' => 'Актеры', 'class' => 'full', 'id' => 'film_actors')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'full', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save')) !!}
			{!! Form::close() !!}
	 	</div><!--
      	--><div class="side_note">
        	<!--p>Рекомендации к правильному заполнению:</p>
            	<ul>
            		<li>Если название фильма появится в предлагаемых вариантах, чтобы отличить ваш укажите в скобках страну или год</li>
              		<li>Элементы разделяйте точкой с запятой&nbsp;—&nbsp;в названиях жанров могут быть запятые, так что, они не подходят</li>
              		<li>Продолжительность указывайте в минутах цифрами</li>
              		<li>Указывайте только исполнителей главных ролей, их редко бывает больше пяти. Исключение составляют фильмы с большой концентрацией звезд</li>
              	</ul-->
			<p><span class="symlink" onclick="expand_genres(this, 'films_genres')">Жанры фильмов</span></p>
			{!! Helpers::object2list($genres, 'films_genres') !!}

            <p><span class="symlink" onclick="expand_genres(this, 'countries')">Страны</span></p>
            {!! Helpers::object2list($countries, 'countries') !!}

            <p><span class="symlink" onclick="expand_genres(this, 'collections_list')">Коллекции</span></p>
            {!! Helpers::object2list($collections, 'collections_list') !!}
        </div>
    </div>

    <script>

        $(document).ready(function() {

            var director = $('#film_director');
            var producer = $('#film_producer');
            var screenwriter = $('#film_screenwriter');
            var actors = $('#film_actors');
            var collections = $('#collections');
            // film
            $('#film_name').autocomplete({
                source: "{!! URL::action('SearchController@film_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            $('#film_genre').autocomplete({
                source: "{!! URL::action('SearchController@film_genre') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            director.autocomplete({
                source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            producer.autocomplete({
                source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            screenwriter.autocomplete({
                source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            actors.autocomplete({
                source: "{!! URL::action('SearchController@person_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            collections.autocomplete({
                source: "{!! URL::action('SearchController@collection_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            $('#film_country').autocomplete({
                source: "{!! URL::action('SearchController@country_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            bind_genres('films_genres', 'film_genre');
            bind_genres('countries', 'film_country');
            bind_genres('collections_list', 'collections');

            director.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });
            producer.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });
            screenwriter.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });
            actors.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });

        });

    </script>
    @endif

    @if('games' == $section)
	<div id="add_game"  class="visible">
	 	<div class="half">
			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_game', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'games') !!}
				<p>{!! Form::text('game_name', $value = Input::get('game_name', ''), $attributes = array('placeholder' => 'Название игры', 'id' => 'game_name', 'class' => 'full')) !!}</p>
				<p>{!! Form::text('game_alt_name', $value = Input::get('game_alt_name', ''), $attributes = array('placeholder' => 'Альтернативное или оригинальное название игры', 'id' => 'game_alt_name', 'class' => 'full')) !!}</p>
                <p>{!! Form::text('game_developer', $value = Input::get('game_developer', ''), $attributes = array('placeholder' => 'Разработчик', 'class' => 'full', 'id' => 'game_developer')) !!}</p>
                <p>{!! Form::text('game_publisher', $value = Input::get('game_publisher', ''), $attributes = array('placeholder' => 'Издатель', 'class' => 'full', 'id' => 'game_publisher')) !!}</p>
                <p>{!! Form::textarea('game_description', $value = null, $attributes = array('placeholder' => 'Описание', 'class' => 'full', 'id' => 'game_description')) !!}</p>
				<p>{!! Form::text('game_genre', $value = Input::get('game_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'full', 'id' => 'game_genre')) !!}</p>
				<p>{!! Form::text('game_platform', $value = Input::get('game_platform', ''), $attributes = array('placeholder' => 'Платформа', 'class' => 'full', 'id' => 'game_platform')) !!}</p>
				<p>{!! Form::text('game_year', $value = Input::get('game_year', ''), $attributes = array('placeholder' => 'Год выпуска', 'class' => 'third')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'full', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save')) !!}
			{!! Form::close() !!}
	 	</div><!--
      	--><div class="side_note">
        	<!--p>Рекомендации к правильному заполнению:</p>
            	<ul>
              		<li>Элементы разделяйте точкой с запятой&nbsp;—&nbsp;в названиях жанров могут быть запятые, так что, они не подходят</li>
              		<li>Аддоны, паки, сборки и коллекции не нужны&nbsp;—&nbsp;только полноценные игры. При очень большом женлании информацию о дополнениях можно добавить в описание</li>
              		<li>Предпочтение в размещении отдается более известным и популярным играм</li>
              		<li>Азартные игры и малоизвестные спортивные симуляторы не нужны</li>
              		<li>Флеш- и джава-игры в большинстве случаев не нужны</li>
              	</ul-->
            <p><span class="symlink" onclick="expand_genres(this, 'games_genres')">Жанры игр</span></p>
            {!! Helpers::object2list($genres, 'games_genres') !!}

            <p><span class="symlink" onclick="expand_genres(this, 'platforms')">Платформы</span></p>
            {!! Helpers::object2list($platforms, 'platforms') !!}

            <p><span class="symlink" onclick="expand_genres(this, 'collections_list')">Коллекции</span></p>
            {!! Helpers::object2list($collections, 'collections_list') !!}
        </div>
    </div>

	<script>
		$(document).ready(function(){

            var developer = $('#game_developer');
            var publisher = $('#game_publisher');
            var collections = $('#collections');
            // game
            $('#game_name').autocomplete({
                source: "{!! URL::action('SearchController@game_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            $('#game_genre').autocomplete({
                source: "{!! URL::action('SearchController@game_genre') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            $('#game_platform').autocomplete({
                source: "{!! URL::action('SearchController@platform_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            developer.autocomplete({
                source: "{!! URL::action('SearchController@company_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            publisher.autocomplete({
                source: "{!! URL::action('SearchController@company_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            collections.autocomplete({
                source: "{!! URL::action('SearchController@collection_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            bind_genres('games_genres', 'game_genre');
            bind_genres('platforms', 'game_platform');
            bind_genres('collections_list', 'collections');

            developer.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });
            publisher.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });
            collections.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });

		});
	</script>
    @endif

    @if('albums' == $section)
	<div id="add_game"  class="visible">
	 	<div class="half">
			{!! Form::open(array('action' => 'DatabaseController@save', 'class' => 'add_album', 'method' => 'POST', 'files' => true)) !!}
                {!! Form::hidden('action', $value = 'add') !!}
				{!! Form::hidden('section', $value = 'albums') !!}
				<p>{!! Form::text('album_name', $value = Input::get('album_name', ''), $attributes = array('placeholder' => 'Название альбома', 'id' => 'album_name', 'class' => 'full')) !!}</p>
                <p>{!! Form::text('album_band', $value = Input::get('album_band', ''), $attributes = array('placeholder' => 'Авторы и исполнители', 'class' => 'full', 'id' => 'album_band')) !!}</p>
                <p>{!! Form::textarea('album_description', $value = null, $attributes = array('placeholder' => 'Описание', 'class' => 'full', 'id' => 'album_description')) !!}</p>
				<p>{!! Form::text('album_genre', $value = Input::get('album_genre', ''), $attributes = array('placeholder' => 'Жанр', 'class' => 'full', 'id' => 'album_genre')) !!}</p>
				<p>{!! Form::text('album_year', $value = Input::get('album_year', ''), $attributes = array('placeholder' => 'Год выпуска', 'class' => 'third')) !!}</p>
                <p>{!! Form::text('collections', $value = Input::get('collections', ''), $attributes = array('placeholder' => 'Коллекции', 'class' => 'full', 'id' => 'collections')) !!}</p>
				<p><b>Обложка</b> {!! Form::file('cover'); !!}</p>
				{!! Form::submit('Сохранить', $attributes = array('id' => 'comment_save')) !!}
			{!! Form::close() !!}
	 	</div><!--
      	--><div class="side_note">
        	<!--p>Рекомендации к правильному заполнению:</p>
            	<ul>
              		<li>Элементы разделяйте точкой с запятой&nbsp;—&nbsp;в названиях жанров могут быть запятые, так что, они не подходят</li>
              		<li>Аддоны, паки, сборки и коллекции не нужны&nbsp;—&nbsp;только полноценные игры. При очень большом женлании информацию о дополнениях можно добавить в описание</li>
              		<li>Предпочтение в размещении отдается более известным и популярным играм</li>
              		<li>Азартные игры и малоизвестные спортивные симуляторы не нужны</li>
              		<li>Флеш- и джава-игры в большинстве случаев не нужны</li>
              	</ul-->
            <p><span class="symlink" onclick="expand_genres(this, 'albums_genres')">Жанры музыки</span></p>
            {!! Helpers::object2list($genres, 'albums_genres') !!}

            <p><span class="symlink" onclick="expand_genres(this, 'collections_list')">Коллекции</span></p>
            {!! Helpers::object2list($collections, 'collections_list') !!}
        </div>
    </div>

    <script>
        $(document).ready(function(){

            var bands = $('#album_bands');
            var collections = $('#collections');
            // game
            $('#album_name').autocomplete({
                source: "{!! URL::action('SearchController@album_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            $('#game_genre').autocomplete({
                source: "{!! URL::action('SearchController@album_genre') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            bands.autocomplete({
                source: "{!! URL::action('SearchController@band_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            collections.autocomplete({
                source: "{!! URL::action('SearchController@collection_name') !!}", // url-адрес
                minLength: 3, // минимальное количество для совершения запроса
                delay: 500
            });
            bind_genres('albums_genres', 'album_genre');
            bind_genres('album_bands', 'album_band');
            bind_genres('collections_list', 'collections');

            bands.blur(function(event) {
                $(this).val($(this).val().replace(/,/g, ';'));
            });

        });
    </script>
    @endif

@else
	{!! Helpers::reg2add() !!}
@endif

@stop