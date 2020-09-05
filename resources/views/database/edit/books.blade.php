@php
    /** @var $element */
    $writers = view('widgets.string-collection-names', array('collection' => $element->writers, 'delimiter' => '; ', 'no_quotes' => true));
    $books_publishers = view('widgets.string-collection-names', array('collection' => $element->books_publishers, 'delimiter' => '; ', 'no_quotes' => true));
    $genres = view('widgets.string-collection-names', array('collection' => $element->genres, 'delimiter' => '; ', 'no_quotes' => true));
    $collections = view('widgets.string-collection-names', array('collection' => $element->collections, 'delimiter' => '; ', 'no_quotes' => true));
@endphp
@extends('layouts.default')
@section('title'){!! $element->name !!}@stop
@section('subtitle') Редактировать @stop
@section('content')
    @if(Auth::check())
        @if(count($errors))
            <div class="row mt-5">
                <div class="col-md-12">
                    @foreach ($errors->all() as $error)
                        <p>{!! $error !!}</p>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="row mt-5">
            <div class="col-md-9">
                <div class="card @include('widgets.card-class')">
                    {!! Form::open(array('action' => 'Admin\DatabaseController@save', 'class' => 'add_book', 'method' => 'POST', 'files' => true)) !!}
                    <div class="card-header">
                        <h1 class="card-title">@yield('title')</h1>
                        <h2 class="card-subtitle mb-2 text-muted">@yield('subtitle')</h2>
                        {!! Form::hidden('action', 'edit') !!}
                        {!! Form::hidden('section', 'books') !!}
                        {!! Form::hidden('element_id', $element->id) !!}
                    </div>
                    <div class="card-body">
                        <p>{!! Form::text('name', $element->name, array('placeholder' => 'Название книги', 'id' => 'name', 'class' => 'form-control w-100')) !!}</p>
                        <p>{!! Form::text('alt_name', implode('; ', $element->alt_name), array('placeholder' => 'Альтернативное или оригинальное название книги', 'id' => 'alt_name', 'class' => 'form-control w-100')) !!}</p>
                        <p>{!! Form::text('writers', $writers, array('placeholder' => 'Автор', 'class' => 'form-control w-100', 'id' => 'writers')) !!}</p>
                        <p>{!! Form::text('books_publishers', $books_publishers, array('placeholder' => 'Издатель', 'class' => 'form-control w-100', 'id' => 'books_publishers')) !!}</p>
                        <p>{!! Form::textarea('description', $element->description, array('placeholder' => 'Аннотация', 'class' => 'form-control w-100', 'id' => 'annotation')) !!}</p>
                        <p>{!! Form::text('genres', $genres, array('placeholder' => 'Жанр', 'class' => 'form-control w-100', 'id' => 'genres')) !!}</p>
                        <p>{!! Form::text('year', $element->year, array('placeholder' => 'Год написания', 'class' => 'form-control w-25')) !!}</p>
                        <p>{!! Form::text('collections', $collections, array('placeholder' => 'Коллекции', 'class' => 'form-control w-100', 'id' => 'collections')) !!}</p>
                        <b>Обложка</b> {!! Form::file('cover'); !!}
                    </div>
                    <div class="card-footer">
                        {!! Form::submit('Сохранить', array('id' => 'save', 'class' => 'btn btn-secondary', 'role' => 'button')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="card @include('widgets.card-class')">
                    <img class="card-img-top" src="{!! ElementsHelper::getCover($section, $element->id) !!}" alt="">
                    <div class="card-body text-center">
                        <div class="btn-group">
                            {!! DummyHelper::getExtLink('fantlab', $element->name); !!}
                            {!! DummyHelper::getExtLink('wiki', $element->name); !!}
                        </div>
                        <div class="btn-group mt-3">
                            {!! DummyHelper::getExtLink('yandex', $element->name); !!}
                            {!! DummyHelper::getExtLink('yandex_images', $element->name); !!}
                        </div>
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#books_genres_container" aria-expanded="false" aria-controls="books_genres_container">
                        Жанры книг
                    </div>
                    <div class="collapse" id="books_genres_container">
                        @include('widgets.list-collection-names', array('id' => 'genres_list', 'collection' => ElementsHelper::getGenres($section)))
                    </div>
                    <div class="card-header" data-toggle="collapse" data-target="#collections_list_container" aria-expanded="false" aria-controls="collections_list_container">
                        Коллекции
                    </div>
                    <div class="collapse" id="collections_list_container">
                        @include('widgets.list-collection-names', array('id' => 'collections_list', 'collection' => ElementsHelper::getCollections()))
                    </div>
                    <div id="transfer" class="card-footer text-center">
                        @include('widgets.card-transfer', array('controller' => 'BooksController', 'element' => $element))
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="/data/js/admin/books.js"></script>
    @else
        {!! DummyHelper::regToAdd() !!}
    @endif
@stop
