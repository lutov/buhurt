@extends('layouts.default')
@section('title')Главная страница@stop
@section('subtitle')@stop
@section('keywords')бугурт, оценки, коллекция, база, фильмы, книги, игры@stop
@section('description')«Бугурт» помогает найти забытые книги, фильмы, игры и музыку, составить коллекцию, хранить оценки и подобрать новые интересные произведения@stop
@section('content')
    <div class="row">
        @include('section.cards.item', array('section' => SectionsHelper::getSection('books'), 'element' => ElementsHelper::getRecommend($request, 'books')))
        @include('section.cards.item', array('section' => SectionsHelper::getSection('films'), 'element' => ElementsHelper::getRecommend($request, 'films')))
        @include('section.cards.item', array('section' => SectionsHelper::getSection('games'), 'element' => ElementsHelper::getRecommend($request, 'games')))

        @include('section.items', array('section' => SectionsHelper::getSection('books'), 'elements' => $books))
        @include('section.items', array('section' => SectionsHelper::getSection('films'), 'elements' => $films))
        @include('section.items', array('section' => SectionsHelper::getSection('games'), 'elements' => $games))

        @include('section.cards.item', array('section' => SectionsHelper::getSection('albums'), 'element' => ElementsHelper::getRecommend($request, 'albums')))
        @include('section.items', array('section' => SectionsHelper::getSection('albums'), 'elements' => $albums))
    </div>
@stop
