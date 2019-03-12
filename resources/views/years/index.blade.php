@extends('layouts.default')

@section('title'){{$title}}@stop

@section('subtitle'){{$subtitle}}@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

    <div class="row mt-5">

        <div class="col-md-12" style="column-count: 10;">

            <ul>
                <li><a href="/years/books">Книги</a></li>
                <li><a href="/years/films">Фильмы</a></li>
                <li><a href="/years/games">Игры</a></li>
                <li><a href="/years/albums">Альбомы</a></li>
                <li><a href="/years/memes">Мемы</a></li>
            </ul>

        </div>

    </div>

@stop