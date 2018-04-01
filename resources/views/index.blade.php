@extends('layouts.default')

@section('title')Бугурт@stop

@section('subtitle')@stop

@section('keywords')бугурт, оценки, коллекция, база, фильмы, книги, игры@stop
@section('description')«Бугурт» помогает найти забытые книги, фильмы, игры и музыку, составить коллекцию, хранить оценки и подобрать новые интересные произведения@stop

@section('content')

	<section class="text-center">
		<h1 class="mt-5">@yield('title')</h1>
		<h2 class="mb-3">@yield('subtitle')</h2>
	</section>

	<section class="text-center mt-5">
		<h2 id="section">Рекомендации</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! ElementsHelper::getHeader(); !!}
			{!! ElementsHelper::getRecommend($request, 'books'); !!}
			{!! ElementsHelper::getRecommend($request, 'films'); !!}
			{!! ElementsHelper::getRecommend($request, 'games'); !!}
			{!! ElementsHelper::getRecommend($request, 'albums'); !!}
			{!! ElementsHelper::getFooter(); !!}

		</div>

	</div>

	<section class="text-center mt-5">
		<h2 id="section">Последние обновления</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			<?php
				$options = array(
					'header' => false,
					'paginate' => false,
					'footer' => false,
				);
			?>
			{!! ElementsHelper::getHeader(); !!}
			{!! ElementsHelper::getElements($request, $books, 'books', $options) !!}
			{!! ElementsHelper::getElements($request, $films, 'films', $options) !!}
			{!! ElementsHelper::getElements($request, $games, 'games', $options) !!}
			{!! ElementsHelper::getElements($request, $albums, 'albums', $options) !!}
			{!! ElementsHelper::getFooter(); !!}

		</div>

	</div>

@stop