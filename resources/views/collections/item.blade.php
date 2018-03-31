@extends('layouts.default')

@section('title'){!! $collection->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5">@yield('title')</h1>
		<h2 class="pb-3">@yield('subtitle')</h2>
		<ul class="list-inline">

			@if(count($books))<li class="list-inline-item"><a href="#books">Книги</a></li>@endif
			@if(count($films))<li class="list-inline-item"><a href="#films">Фильмы</a></li>@endif
			@if(count($games))<li class="list-inline-item"><a href="#games">Игры</a></li>@endif

		</ul>
	</section>

	<div class="row mt-5 align-top">

		<div class="col-md-3">

			@if(!empty($cover)) <img src="/data/img/collections/{!! $section !!}/{!! $cover !!}.jpg" alt="{!! $collection->name !!}" class="img-fluid" /> @endif

		</div>

		<div class="col-md-9">

			@if(!empty($collection->description)) <p>{!! nl2br($collection->description) !!}</p> @endif

		</div>

	</div>

	@if(count($books))

		<section class="text-center mt-5">
			<h2 id="books">Книги</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $books, 'books') !!}
			</div>

		</div>

	@endif

	@if(count($films))

		<section class="text-center mt-5">
			<h2 id="films">Фильмы</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $films, 'films') !!}
			</div>

		</div>

	@endif

    @if(count($games))

		<section class="text-center mt-5">
			<h2 id="games">Игры</h2>
		</section>

		<div class="row mt-5">

			<div class="col-md-12">
				{!! ElementsHelper::getElements($request, $games, 'games') !!}
			</div>

		</div>

    @endif

@stop