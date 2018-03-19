@extends('layouts.default')

@section('title')
	«{!! $query !!}»
@stop

@section('subtitle')
	Поиск
@stop

@section('content')

  	<h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

  	@if(count($persons))
	<h3>Люди</h3>
	{!! Helpers::get_elements($persons, 'persons', array(), false) !!}
	@endif

	@if(count($books))
	<h3>Книги</h3>
	{!! Helpers::get_elements($books, 'books', array(), false) !!}
	@endif

	@if(count($films))
	<h3>Фильмы</h3>
	{!! Helpers::get_elements($films, 'films', array(), false) !!}
	@endif

	@if(count($games))
	<h3>Игры</h3>
	{!! Helpers::get_elements($games, 'games', array(), false) !!}
	@endif

	@if(count($albums))
	<h3>Альбомы</h3>
	{!! Helpers::get_elements($albums, 'albums', array(), false) !!}
	@endif

	@if(count($bands))
	<h3>Группы</h3>
	{!! Helpers::get_elements($bands, 'bands', array(), false) !!}
	@endif

@stop