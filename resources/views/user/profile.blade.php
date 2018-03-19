@extends('layouts.default')

@section('title')
	{!! $user->username !!}
@stop

@section('subtitle')
	Профиль
@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

    <div class="element_additional_info">
		<p>Зарегистрирован {!! LocalizedCarbon::instance($user->created_at)->diffForHumans() !!}</p>
        @if(!empty($city))<p>Предполагаемый город: {!! $city->name !!}</p>@endif
    </div>

	<div class="element_card">
		<div class="element_img">
			<img src="/data/img/avatars/{!! $avatar !!}.jpg" alt="{!! $user->username !!}" />
		</div><!--
		--><div class="element_description">

			{!! Helpers::render_achievements($achievements, $user_achievements) !!}
			
			@if(!empty($chart_rates))
				
				<script src="/data/js/chart/Chart.js"></script>
				<div class="half">
					<canvas id="chart_rates"></canvas>
				</div>
				
				<script>
				    var randomColorFactor = function() {
						return Math.round(Math.random() * 255);
					};
					var randomColor = function() {
						return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)';
					};
					
					var barChartData = {
					labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
					datasets: [{
						label: 'Выставлено оценок',
						backgroundColor: randomColor(),
						data: [{!! implode($chart_rates, ', ') !!}]
					},]

				};
				
					window.onload = function() {
						var ctx = $("#chart_rates");
						window.myBar = new Chart(ctx, {
							type: 'bar',
							data: barChartData,
							options: {
								// Elements options apply to all of the options unless overridden in a dataset
								// In this case, we are setting the border of each bar to be 2px wide and green
								elements: {
									rectangle: {
										borderWidth: 2,
										borderColor: randomColor(),
										borderSkipped: 'bottom'
									}
								},
								responsive: true,
								legend: {
									position: 'top',
								},
								title: {
									display: false,
								}
							}
						});

					};
				</script>
			
			@endif

            @if(0 != $books_rated || 0 != $films_rated || 0 != $games_rated)
			<p>
				Оценил

				@if(0 != $books_rated)
				<a href="{!! URL::action('UserController@rates', array($user->id, 'books')) !!}" >{!! Helpers::number($books_rated, array('книгу', 'книги', 'книг')) !!}</a>,
				@endif
				@if(0 != $films_rated)
				<a href="{!! URL::action('UserController@rates', array($user->id, 'films')) !!}" >{!! Helpers::number($films_rated, array('фильм', 'фильма', 'фильмов')) !!}</a>,
				@endif
				@if(0 != $games_rated)
				<a href="{!! URL::action('UserController@rates', array($user->id, 'games')) !!}" >{!! Helpers::number($games_rated, array('игру', 'игры', 'игр')) !!}</a>,
				@endif
				@if(0 != $albums_rated)
				<a href="{!! URL::action('UserController@rates', array($user->id, 'albums')) !!}" >{!! Helpers::number($albums_rated, array('альбом', 'альбома', 'альбомов')) !!}</a>
				@endif
			</p>
            @endif

            @if(0 != $books_wanted || 0 != $films_wanted || 0 != $games_wanted)
			<p>
				Хочет
				@if(0 != $books_wanted)
                прочесть <a href="{!! URL::action('UserController@wanted', array($user->id, 'books')) !!}" >{!! Helpers::number($books_wanted, array('книгу', 'книги', 'книг')) !!}</a>,
				@endif
				@if(0 != $films_wanted)
				посмотреть <a href="{!! URL::action('UserController@wanted', array($user->id, 'films')) !!}" >{!! Helpers::number($films_wanted, array('фильм', 'фильма', 'фильмов')) !!}</a>,
				@endif
				@if(0 != $games_wanted)
				сыграть в <a href="{!! URL::action('UserController@wanted', array($user->id, 'games')) !!}" >{!! Helpers::number($games_wanted, array('игру', 'игры', 'игр')) !!}</a>,
				@endif
                @if(0 != $albums_wanted)
                    слушать <a href="{!! URL::action('UserController@wanted', array($user->id, 'albums')) !!}" >{!! Helpers::number($albums_wanted, array('альбом', 'альбома', 'альбомов')) !!}</a>
                @endif
			</p>
            @endif

            @if(0 != $books_not_wanted || 0 != $films_not_wanted || 0 != $games_not_wanted)
			<p>

                Не хочет
				@if(0 != $books_not_wanted)
                читать <a href="{!! URL::action('UserController@not_wanted', array($user->id, 'books')) !!}" >{!! Helpers::number($books_not_wanted, array('книгу', 'книги', 'книг')) !!}</a>,
				@endif
				@if(0 != $films_not_wanted)
				смотреть <a href="{!! URL::action('UserController@not_wanted', array($user->id, 'films')) !!}" >{!! Helpers::number($films_not_wanted, array('фильм', 'фильма', 'фильмов')) !!}</a>,
				@endif
				@if(0 != $games_not_wanted)
				играть в <a href="{!! URL::action('UserController@not_wanted', array($user->id, 'games')) !!}" >{!! Helpers::number($games_not_wanted, array('игру', 'игры', 'игр')) !!}</a>,
				@endif
                @if(0 != $albums_not_wanted)
                    слушать <a href="{!! URL::action('UserController@not_wanted', array($user->id, 'albums')) !!}" >{!! Helpers::number($albums_not_wanted, array('альбом', 'альбома', 'альбомов')) !!}</a>
                @endif
			</p>
            @endif

            @if(count($fav_gens_books) || count($fav_gens_films) || count($fav_gens_games) || count($fav_gens_albums))
                <h3>Любимые жанры</h3>

                @if(count($fav_gens_books))<p>Книги: {!! Helpers::array2string($fav_gens_books, ', ', '/genres/books/'); !!}</p>@endif
                @if(count($fav_gens_films))<p>Фильмы: {!! Helpers::array2string($fav_gens_films, ', ', '/genres/films/'); !!}</p>@endif
                @if(count($fav_gens_games))<p>Игры: {!! Helpers::array2string($fav_gens_games, ', ', '/genres/games/'); !!}</p>@endif
                @if(count($fav_gens_albums))<p>Альбомы: {!! Helpers::array2string($fav_gens_albums, ', ', '/genres/albums/'); !!}</p>@endif
            @endif

            @if(Auth::check() && Auth::user()->id == $user->id)
                <h3>Аватар</h3>
                {!! Form::open(array('action' => 'UserController@avatar', 'class' => 'avatar', 'method' => 'POST', 'files' => true)) !!}
                <p>{!! Form::file('avatar'); !!}</p>
                {!! Form::submit('Загрузить', $attributes = array('id' => 'upload_avatar')) !!}
                {!! Form::close() !!}

                <h3>Безопасность</h3>
                <p><a href="{!! URL::action('UserController@change_password') !!}">Сменить пароль</a></p>

                <h3>Настройки</h3>
                {!! Form::open(array('url' => 'user/'.$user->id.'/options', 'class' => 'options', 'method' => 'POST')) !!}
                <?php
                foreach($options as $option) {

                    $status = false;
                    $option_id = $option->id;
                    $status = in_array($option_id, $user_options);

                    echo '<p>'.Form::hidden($option->name, $value = '0', $attributes = array('autocomplete' => 'off')).
                    Form::checkbox($option->name, 1, $status, $attributes = array(
                        'id' => $option->name,
                        'autocomplete' => 'off')
                    ).' <label for="'.$option->name.'">'.$option->description.'</label></p>';
                }
                ?>
                {!! Form::submit('Сохранить', $attributes = array('id' => 'set_options')) !!}
                {!! Form::close() !!}
            @endif

		</div>

	</div>

		<script>
			$(document).tooltip({
            	position: { my: "left+5 center", at: "right center" }
           	});
		</script>

@stop