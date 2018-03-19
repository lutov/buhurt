@extends('layouts.default')

@section('title')Бугурт@stop

@section('subtitle')Свободная система оценок@stop

@section('keywords')бугурт, оценки, коллекция, база, фильмы, книги, игры@stop
@section('description')«Бугурт» помогает найти забытые книги, фильмы, игры и музыку, составить коллекцию, хранить оценки и подобрать новые интересные произведения@stop

@section('content')

	<section class="text-center">
		<h1 class="pt-5 pb-3">@yield('title')</h1>
		<!--h2 class="pb-3">@yield('subtitle')</h2-->
	</section>

	<div id="mainpage">

		<section class="text-center"> <h2 class="pb-3">Рекомендации</h2></section>
		{!! ElementsHelper::getHeader(); !!}
		{!! ElementsHelper::getRecommend('books'); !!}
		{!! ElementsHelper::getRecommend('films'); !!}
		{!! ElementsHelper::getRecommend('games'); !!}
		{!! ElementsHelper::getRecommend('albums'); !!}
		{!! ElementsHelper::getFooter(); !!}


		<section class="text-center"><h2 class="pb-3">Последние обновления</h2></section>
		<?php
			$options = array(
				'header' => false,
				'paginate' => false,
				'footer' => false,
			);
		?>
		{!! ElementsHelper::getHeader(); !!}
		{!! ElementsHelper::getElements($books, 'books', $options) !!}
		{!! ElementsHelper::getElements($films, 'films', $options) !!}
		{!! ElementsHelper::getElements($games, 'games', $options) !!}
		{!! ElementsHelper::getElements($albums, 'albums', $options) !!}
		{!! ElementsHelper::getFooter(); !!}

	</div>

@stop