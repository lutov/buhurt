@extends('layouts.default')

@section('title')
	Совет
@stop

@section('subtitle')

@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
	</section>

	<ul class="nav nav-tabs" id="myTab" role="tablist">

		<li class="nav-item">
			<a class="nav-link active" id="home-tab" data-toggle="tab" href="#book_tab" role="tab" aria-controls="book" aria-selected="true">Почитать</a>
		</li>

		<li class="nav-item">
			<a class="nav-link" id="profile-tab" data-toggle="tab" href="#film_tab" role="tab" aria-controls="film" aria-selected="false">Посмотреть</a>
		</li>

		<li class="nav-item">
			<a class="nav-link" id="contact-tab" data-toggle="tab" href="#game_tab" role="tab" aria-controls="game" aria-selected="false">Поиграть</a>
		</li>

		<li class="nav-item">
			<a class="nav-link" id="contact-tab" data-toggle="tab" href="#album_tab" role="tab" aria-controls="album" aria-selected="false">Послушать</a>
		</li>

	</ul>
	<div class="tab-content" id="myTabContent">

		<div class="tab-pane fade show active" id="book_tab" role="tabpanel" aria-labelledby="book-tab">

			@if(isset($book->id))

				<div itemscope itemtype="http://schema.org/Book">

					{!! ElementsHelper::getCardHeader($request, 'books', $book, $book->options) !!}

					{!! ElementsHelper::getCardBody($request, 'books', $book, $book->options) !!}

					{!! ElementsHelper::getCardFooter($request, 'books', $book, $book->options) !!}

				</div>

				{!! ElementsHelper::getCardScripts('books', $book->id) !!}

			@else



			@endif

		</div>

		<div class="tab-pane fade" id="film_tab" role="tabpanel" aria-labelledby="film-tab">

			@if(isset($film->id))

				<div itemscope itemtype="http://schema.org/Movie">

					{!! ElementsHelper::getCardHeader($request, 'films', $film, $film->options) !!}

					{!! ElementsHelper::getCardBody($request, 'films', $film, $film->options) !!}

					{!! ElementsHelper::getCardFooter($request, 'films', $film, $film->options) !!}

				</div>

				{!! ElementsHelper::getCardScripts('films', $film->id) !!}

			@else



			@endif

		</div>

		<div class="tab-pane fade" id="game_tab" role="tabpanel" aria-labelledby="game-tab">

			@if(isset($game->id))

				<div itemscope itemtype="http://schema.org/Game">

					{!! ElementsHelper::getCardHeader($request, 'games', $game, $game->options) !!}

					{!! ElementsHelper::getCardBody($request, 'games', $game, $game->options) !!}

					{!! ElementsHelper::getCardFooter($request, 'games', $game, $game->options) !!}

				</div>

				{!! ElementsHelper::getCardScripts('games', $game->id) !!}

			@else



			@endif

		</div>

		<div class="tab-pane fade" id="album_tab" role="tabpanel" aria-labelledby="album-tab">

			@if(isset($album->id))

				<div itemscope itemtype="http://schema.org/MusicAlbum">

					{!! ElementsHelper::getCardHeader($request, 'albums', $album, $album->options) !!}

					{!! ElementsHelper::getCardBody($request, 'albums', $album, $album->options) !!}

					{!! ElementsHelper::getCardFooter($request, 'albums', $album, $album->options) !!}

				</div>

				{!! ElementsHelper::getCardScripts('albums', $album->id) !!}

			@else



			@endif

		</div>

	</div>

@stop