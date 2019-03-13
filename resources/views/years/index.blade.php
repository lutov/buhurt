@extends('layouts.default')

@section('title'){{$title}}@stop

@section('subtitle'){{$subtitle}}@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

    <div class="row mt-5">

        <div class="col-md-12 text-center">

            <div class="btn-group" role="group" aria-label="Sections">

                <a role="button" class="btn btn-outline-primary" href="/years/books">Книги</a>
                <a role="button" class="btn btn-outline-primary" href="/years/films">Фильмы</a>
                <a role="button" class="btn btn-outline-primary" href="/years/games">Игры</a>
                <a role="button" class="btn btn-outline-primary" href="/years/albums">Альбомы</a>
                <a role="button" class="btn btn-outline-primary" href="/years/memes">Мемы</a>

            </div>

        </div>

    </div>

@stop