@extends('layouts.default')

@section('title')Бугурт@stop

@section('subtitle')@stop

@section('keywords')бугурт, оценки, коллекция, база, фильмы, книги, игры@stop
@section('description')«Бугурт» помогает найти забытые книги, фильмы, игры и музыку, составить коллекцию, хранить оценки и подобрать новые интересные произведения@stop

@section('content')

	<section class="text-center mt-5 mb-3">
		<h1 class="">@yield('title')</h1>
		<h2 id="recommendations_title">Рекомендации</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! ElementsHelper::getHeader($request, $options['sort_list']); !!}
			{!! ElementsHelper::getRecommend($request, 'books'); !!}
			{!! ElementsHelper::getRecommend($request, 'films'); !!}
			{!! ElementsHelper::getRecommend($request, 'games'); !!}
			{!! ElementsHelper::getRecommend($request, 'albums'); !!}
			{!! ElementsHelper::getFooter(); !!}

		</div>

	</div>

	<section class="text-center mt-5 mb-3">
		<h2 id="updates_title">Обновления</h2>
	</section>

	<div class="row mt-5">

		<div class="col-md-12">

			{!! ElementsHelper::getHeader($request, $options['sort_list']); !!}

			<?php $options['wanted'] = $wanted['books']; $options['unwanted'] = $unwanted['books']; ?>
			{!! ElementsHelper::getElements($request, $books, 'books', $options) !!}

			<?php $options['wanted'] = $wanted['films']; $options['unwanted'] = $unwanted['films']; ?>
			{!! ElementsHelper::getElements($request, $films, 'films', $options) !!}

			<?php $options['wanted'] = $wanted['games']; $options['unwanted'] = $unwanted['games']; ?>
			{!! ElementsHelper::getElements($request, $games, 'games', $options) !!}

			<?php $options['wanted'] = $wanted['albums']; $options['unwanted'] = $unwanted['albums']; ?>
			{!! ElementsHelper::getElements($request, $albums, 'albums', $options) !!}

			{!! ElementsHelper::getFooter(); !!}

		</div>

	</div>

@stop