@extends('layouts.default')

@section('title')Расширенный поиск@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

    <div class="row mt-5">

        <div class="col-md-12">

            <ul class="list-unstyled">

                <li><a href="/search/advanced/persons">Персоны</a></li>
                <li><a href="/search/advanced/companies">Компании</a></li>
                <li><a href="/search/advanced/bands">Группы</a></li>
                <li><a href="/search/advanced/collections">Коллекции</a></li>
                <li><a href="/search/advanced/countries">Страны</a></li>
                <li><a href="/search/advanced/platforms">Игровые платформы</a></li>
                <li>
                    По жанрам
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="/search/advanced/books/genres">Книги</a></li>
                        <li class="list-inline-item"><a href="/search/advanced/films/genres">Фильмы</a></li>
                        <li class="list-inline-item"><a href="/search/advanced/games/genres">Игры</a></li>
                    </ul>
                </li>
                <li>
                    По годам
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="/search/advanced/books/years">Книги</a></li>
                        <li class="list-inline-item"><a href="/search/advanced/films/years">Фильмы</a></li>
                        <li class="list-inline-item"><a href="/search/advanced/games/years">Игры</a></li>
                    </ul>
                </li>

            </ul>

        </div>

    </div>

@stop