@extends('layouts.default')

@section('title')
	{!! $collection->name !!}
@stop

@section('subtitle')

@stop

@section('content')

    <h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

    @if(!empty($collection->description))
	<div class="element_card">
        <div class="element_description">
            <p>{!! nl2br($collection->description) !!}</p>
        </div>
    </div>
    @endif

	@if(count($books))
	<h3>Книги</h3>
	{!! Helpers::get_elements($books, 'books', []) !!}
	@endif

	@if(count($films))
	<h3>Фильмы</h3>
	{!! Helpers::get_elements($films, 'films', []) !!}
	@endif

    @if(count($games))
    <h3>Игры</h3>
    {!! Helpers::get_elements($games, 'games', []) !!}
    @endif

@stop