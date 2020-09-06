@extends('layouts.default')

@section('title'){!! $user->username !!}@stop

@section('subtitle')@stop

@section('content')

	<?php

			$has_genres = (count($fav_gens_books) || count($fav_gens_films) || count($fav_gens_games) || count($fav_gens_albums)) ? true : false;
			$has_rates = (!empty($chart_rates)) ? true : false;
			$has_options = (Auth::check() && Auth::user()->id == $user->id) ? true : false;

	?>

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
		<ul class="list-inline">

			@if($has_genres)<li class="list-inline-item"><a href="#genres">Любимые жанры</a></li>@endif

			@if($has_rates)<li class="list-inline-item"><a href="#rates">Распределение оценок</a></li>@endif

			<li class="list-inline-item"><a href="#achievements">Достижения</a></li>

			@if($has_options)<li class="list-inline-item"><a href="#options">Настройки</a></li>@endif

		</ul>
	</section>

	<div class="row">

		<div class="col-md-3 mb-4">

			<div class="card">

			<img src="/data/img/avatars/{!! $avatar !!}.jpg?hash={!! $hash !!}" alt="{!! $user->username !!}" class="card-img-top" />

			</div>

		</div>

		<div class="col-md-9 border rounded p-3">

			<p>Зарегистрирован {!! LocalizedCarbon::instance($user->created_at)->diffForHumans() !!}</p>

			@if(!empty($city) && RolesHelper::isAdmin($request))<p>Предполагаемый город: {!! $city->name !!}</p>@endif

			@if(0 != $books_rated || 0 != $films_rated || 0 != $games_rated)
				<p>
					Оценил
					@if(0 != $books_rated)
						<a href="{!! URL::action('User\UserController@rates', array($user->id, 'books')) !!}" >{!! TextHelper::number($books_rated, array('книгу', 'книги', 'книг')) !!}</a>,
					@endif
					@if(0 != $films_rated)
						<a href="{!! URL::action('User\UserController@rates', array($user->id, 'films')) !!}" >{!! TextHelper::number($films_rated, array('фильм', 'фильма', 'фильмов')) !!}</a>,
					@endif
					@if(0 != $games_rated)
						<a href="{!! URL::action('User\UserController@rates', array($user->id, 'games')) !!}" >{!! TextHelper::number($games_rated, array('игру', 'игры', 'игр')) !!}</a>,
					@endif
					@if(0 != $albums_rated)
						<a href="{!! URL::action('User\UserController@rates', array($user->id, 'albums')) !!}" >{!! TextHelper::number($albums_rated, array('альбом', 'альбома', 'альбомов')) !!}</a>
					@endif
				</p>
			@endif

			@if(0 != $books_wanted || 0 != $films_wanted || 0 != $games_wanted || 0 != $albums_wanted)
				<p>
					Хочет
					@if(0 != $books_wanted)
						прочесть <a href="{!! URL::action('User\UserController@wanted', array($user->id, 'books')) !!}" >{!! TextHelper::number($books_wanted, array('книгу', 'книги', 'книг')) !!}</a>,
					@endif
					@if(0 != $films_wanted)
						посмотреть <a href="{!! URL::action('User\UserController@wanted', array($user->id, 'films')) !!}" >{!! TextHelper::number($films_wanted, array('фильм', 'фильма', 'фильмов')) !!}</a>,
					@endif
					@if(0 != $games_wanted)
						сыграть в <a href="{!! URL::action('User\UserController@wanted', array($user->id, 'games')) !!}" >{!! TextHelper::number($games_wanted, array('игру', 'игры', 'игр')) !!}</a>,
					@endif
					@if(0 != $albums_wanted)
						слушать <a href="{!! URL::action('User\UserController@wanted', array($user->id, 'albums')) !!}" >{!! TextHelper::number($albums_wanted, array('альбом', 'альбома', 'альбомов')) !!}</a>
					@endif
				</p>
			@endif

			@if(0 != $books_unwanted || 0 != $films_unwanted || 0 != $games_unwanted || 0 != $albums_unwanted)
				<p>

					Не хочет
					@if(0 != $books_unwanted)
						читать <a href="{!! URL::action('User\UserController@unwanted', array($user->id, 'books')) !!}" >{!! TextHelper::number($books_unwanted, array('книгу', 'книги', 'книг')) !!}</a>,
					@endif
					@if(0 != $films_unwanted)
						смотреть <a href="{!! URL::action('User\UserController@unwanted', array($user->id, 'films')) !!}" >{!! TextHelper::number($films_unwanted, array('фильм', 'фильма', 'фильмов')) !!}</a>,
					@endif
					@if(0 != $games_unwanted)
						играть в <a href="{!! URL::action('User\UserController@unwanted', array($user->id, 'games')) !!}" >{!! TextHelper::number($games_unwanted, array('игру', 'игры', 'игр')) !!}</a>,
					@endif
					@if(0 != $albums_unwanted)
						слушать <a href="{!! URL::action('User\UserController@unwanted', array($user->id, 'albums')) !!}" >{!! TextHelper::number($albums_unwanted, array('альбом', 'альбома', 'альбомов')) !!}</a>
					@endif
				</p>
			@endif

		</div>

	</div>

	@if($has_genres)
		<section class="text-center mt-5">
			<h2 id="genres">Любимые жанры</h2>
		</section>

		<div class="row">

			<div class="col-md-12 border rounded p-3">

				@if(count($fav_gens_books))<p>Книги: {!! DatatypeHelper::arrayToString($fav_gens_books, ', ', '/genres/'); !!}</p>@endif
				@if(count($fav_gens_films))<p>Фильмы: {!! DatatypeHelper::arrayToString($fav_gens_films, ', ', '/genres/'); !!}</p>@endif
				@if(count($fav_gens_games))<p>Игры: {!! DatatypeHelper::arrayToString($fav_gens_games, ', ', '/genres/'); !!}</p>@endif
				@if(count($fav_gens_albums))<p>Альбомы: {!! DatatypeHelper::arrayToString($fav_gens_albums, ', ', '/genres/'); !!}</p>@endif

			</div>

		</div>
	@endif

	@if($has_rates)

		<script>var chart_rates = [{!! implode(', ', $chart_rates) !!}];</script>
		<script src="/data/vendor/chart/Chart.js"></script>
		<script src="/data/js/rates_chart.js"></script>

		<section class="text-center mt-5">
			<h2 id="rates">Распределение оценок</h2>
		</section>

		<div class="row">

			<div class="col-md-12">

				<canvas id="chart_rates"></canvas>

			</div>

		</div>

	@endif

	<section class="text-center mt-5">
		<h2 id="achievements">Достижения</h2>
	</section>

	<div class="row">

		<div class="col-md-12">

			{!! AchievementsHelper::render($achievements, $user_achievements) !!}

		</div>

	</div>

	@if($has_options)

		<section class="text-center mt-5">
			<h2 id="options">Настройки</h2>
		</section>

		<div class="row mt-5 align-top">

			<div class="col-md-12 border rounded p-3">

				{!! Form::open(array('action' => 'User\UserController@avatar', 'class' => 'avatar', 'method' => 'POST', 'files' => true)) !!}

				<div class="w-50">

					<div class="form-group">
						<label for="avatar">Аватар</label>
						<input type="file" class="form-control-file" name="avatar" id="avatar">
					</div>

				</div>

				<div class="mt-3">
					{!! Form::submit('Загрузить', $attributes = array(
                        'id' => 'upload_avatar',
                        'type' => 'button',
                        'class' => 'btn btn-secondary'
                    )) !!}
				</div>

				{!! Form::close() !!}

			</div>

		</div>

		<div class="row mt-5 align-top">

			<div class="col-md-12 align-top border rounded p-3">

				<h3 class="mb-3">Безопасность</h3>
				<p>
					<a href="{!! URL::action('User\UserController@change_password') !!}" type="button" class="btn btn-secondary">
						Сменить пароль
					</a>
				</p>

			</div>

		</div>

		<div class="row mt-5 align-top">

			<div class="col-md-12 align-top border rounded p-3">

				<h3  class="mb-3">Комментарии</h3>
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
				{!! Form::submit('Сохранить', $attributes = array(
					'id' => 'set_options',
					'type' => 'button',
					'class' => 'btn btn-secondary'
				)) !!}
				{!! Form::close() !!}

			</div>

		</div>

	@endif

	<!--script>
        $(document).tooltip({
            position: { my: "left+5 center", at: "right center" }
        });
	</script-->

@stop