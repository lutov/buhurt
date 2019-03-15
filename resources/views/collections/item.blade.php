@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle')@stop

@section('content')

	<section class="text-center mt-5 mb-3">

		<h1 class="">@yield('title')</h1>

		<ul class="list-inline mt-3">

			@if(count($books))<li class="list-inline-item"><a href="#books">Книги</a></li>@endif
			@if(count($films))<li class="list-inline-item"><a href="#films">Фильмы</a></li>@endif
			@if(count($games))<li class="list-inline-item"><a href="#games">Игры</a></li>@endif

		</ul>

	</section>

	{!! Breadcrumbs::render('element', $element) !!}

	<div itemscope itemtype="http://schema.org/CollectionPage">

		<?php
		$info = array(
			'cover' => $cover,
		);
		?>

		{!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $info) !!}

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