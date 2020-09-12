@extends('layouts.default')
@section('title')Главная страница@stop
@section('subtitle')@stop
@section('keywords')бугурт, оценки, коллекция, база, фильмы, книги, игры@stop
@section('description')«Бугурт» помогает найти забытые книги, фильмы, игры и музыку, составить коллекцию, хранить оценки и подобрать новые интересные произведения@stop
@section('content')
    <div class="row">
        {!! ElementsHelper::getRecommend($request, 'books'); !!}
        {!! ElementsHelper::getRecommend($request, 'films'); !!}
        {!! ElementsHelper::getRecommend($request, 'games'); !!}

        {!! ElementsHelper::getSection($request, $books, 'books', $options) !!}
        {!! ElementsHelper::getSection($request, $films, 'films', $options) !!}
        {!! ElementsHelper::getSection($request, $games, 'games', $options) !!}

        {!! ElementsHelper::getRecommend($request, 'albums'); !!}
        {!! ElementsHelper::getSection($request, $albums, 'albums', $options) !!}
    </div>
@stop
