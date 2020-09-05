@extends('layouts.default')
@section('title')Главная страница@stop
@section('subtitle')@stop
@section('keywords')бугурт, оценки, коллекция, база, фильмы, книги, игры@stop
@section('description')«Бугурт» помогает найти забытые книги, фильмы, игры и музыку, составить коллекцию, хранить оценки и подобрать новые интересные произведения@stop
@section('content')
	<div class="row mt-3">
		<div class="col-md-12">
			{!! ElementsHelper::getHeader($request, $options); !!}

			{!! ElementsHelper::getRecommend($request, 'books'); !!}
			{!! ElementsHelper::getRecommend($request, 'films'); !!}
			{!! ElementsHelper::getRecommend($request, 'games'); !!}

			{!! ElementsHelper::getElements($request, $books, 'books', $options) !!}
			{!! ElementsHelper::getElements($request, $films, 'films', $options) !!}
			{!! ElementsHelper::getElements($request, $games, 'games', $options) !!}

			{!! ElementsHelper::getRecommend($request, 'albums'); !!}
			{!! ElementsHelper::getElements($request, $albums, 'albums', $options) !!}

			{!! ElementsHelper::getFooter(); !!}
		</div>
	</div>
@stop
