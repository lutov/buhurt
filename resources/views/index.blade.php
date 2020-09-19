@extends('layouts.default')
@section('title')Главная страница@stop
@section('subtitle')@stop
@section('keywords')бугурт, оценки, коллекция, база, фильмы, книги, игры@stop
@section('description')«Бугурт» помогает найти забытые книги, фильмы, игры и музыку, составить коллекцию, хранить оценки и подобрать новые интересные произведения@stop
@section('content')
    <div class="row">
        <div class="@include('card.grid.quarter')">
            @include('section.cards.item', array('section' => SectionsHelper::getSection('books'), 'element' => ElementsHelper::getRecommend($request, 'books')))
        </div>
        <div class="@include('card.grid.quarter')">
            @include('section.cards.item', array('section' => SectionsHelper::getSection('films'), 'element' => ElementsHelper::getRecommend($request, 'films')))
        </div>
        <div class="@include('card.grid.quarter')">
            @include('section.cards.item', array('section' => SectionsHelper::getSection('games'), 'element' => ElementsHelper::getRecommend($request, 'games')))
        </div>

        @include('section.items', array('section' => SectionsHelper::getSection('books'), 'elements' => $books))
        @include('section.items', array('section' => SectionsHelper::getSection('films'), 'elements' => $films))
        @include('section.items', array('section' => SectionsHelper::getSection('games'), 'elements' => $games))

        <div class="@include('card.grid.quarter')">
            @include('section.cards.item', array('section' => SectionsHelper::getSection('albums'), 'element' => ElementsHelper::getRecommend($request, 'albums')))
        </div>
        @include('section.items', array('section' => SectionsHelper::getSection('albums'), 'elements' => $albums))
    </div>
@stop
