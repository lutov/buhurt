@extends('layouts.default')

@section('title'){{$title}}@stop

@section('subtitle'){{$subtitle}}@stop

@section('content')

    <section class="text-center mt-5 mb-3">
        <h1 class="">@yield('title')</h1>
        <h2 class="">@yield('subtitle')</h2>
    </section>

    <div class="row mt-5">

        <div class="col-md-12 text-center">

            <div class="btn-group-vertical" role="group" aria-label="Sections">

                <a role="button" class="btn btn-outline-primary" href="/genres/books">Книги</a>
                <a role="button" class="btn btn-outline-primary" href="/genres/films">Фильмы</a>
                <a role="button" class="btn btn-outline-primary" href="/genres/games">Игры</a>
                <a role="button" class="btn btn-outline-primary" href="/genres/albums">Альбомы</a>
                <a role="button" class="btn btn-outline-primary" href="/genres/memes">Мемы</a>

            </div>

        </div>

    </div>

@stop