@extends('layouts.default')

@section('title')«{!! $query !!}»@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="mt-5">@yield('title')</h1>
		<h2 class="mb-3">@yield('subtitle')</h2>
		<ul class="list-inline">

			@if(count($persons))<li class="list-inline-item"><a href="#persons">Люди</a> ({!! count($persons) !!})</li>@endif
			@if(count($books))<li class="list-inline-item"><a href="#books">Книги</a> ({!! count($books) !!})</li>@endif
			@if(count($films))<li class="list-inline-item"><a href="#films">Фильмы</a> ({!! count($films) !!})</li>@endif
			@if(count($games))<li class="list-inline-item"><a href="#games">Игры</a> ({!! count($games) !!})</li>@endif
			@if(count($albums))<li class="list-inline-item"><a href="#albums">Альбомы</a> ({!! count($albums) !!})</li>@endif
			@if(count($bands))<li class="list-inline-item"><a href="#bands">Группы</a> ({!! count($bands) !!})</li>@endif

		</ul>
	</section>

  	@if(count($persons))

		<section class="text-center mt-5">
			<h2 id="persons">Люди</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $persons, 'persons', $options) !!}
			</div>

		</div>

	@endif

	@if(count($books))

		<section class="text-center mt-5">
			<h2 id="books">Книги</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $books, 'books', $options) !!}
			</div>

		</div>

	@endif

	@if(count($films))

		<section class="text-center mt-5">
			<h2 id="films">Фильмы</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $films, 'films', $options) !!}
			</div>

		</div>

	@endif

	@if(count($games))

		<section class="text-center mt-5">
			<h2 id="games">Игры</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $games, 'games', $options) !!}
			</div>

		</div>

	@endif

	@if(count($albums))

		<section class="text-center mt-5">
			<h2 id="albums">Альбомы</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $albums, 'albums', $options) !!}
			</div>

		</div>

	@endif

	@if(count($bands))

		<section class="text-center mt-5">
			<h2 id="bands">Группы</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $bands, 'bands', $options) !!}
			</div>

		</div>

	@endif

@stop