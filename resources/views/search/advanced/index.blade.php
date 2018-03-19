@extends('layouts.default')

@section('title')
    Расширенный поиск
@stop

@section('subtitle')

@stop

@section('content')

  	<h2>@yield('subtitle')</h2>
  	<h1>@yield('title')</h1>

    <ul>
        <li><a href="/search/advanced/persons">Персоны</a></li>
        <li><a href="/search/advanced/companies">Компании</a></li>
        <li><a href="/search/advanced/bands">Группы</a></li>
        <li><a href="/search/advanced/collections">Коллекции</a></li>
        <li><a href="/search/advanced/countries">Страны</a></li>
        <li><a href="/search/advanced/platforms">Игровые платформы</a></li>
        <li>
            По жанрам
            <ul>
                <li><a href="/search/advanced/books/genres">Книги</a></li>
                <li><a href="/search/advanced/films/genres">Фильмы</a></li>
                <li><a href="/search/advanced/games/genres">Игры</a></li>
            </ul>
        </li>
        <li>
            По годам
            <ul>
                <li><a href="/search/advanced/books/years">Книги</a></li>
                <li><a href="/search/advanced/films/years">Фильмы</a></li>
                <li><a href="/search/advanced/games/years">Игры</a></li>
            </ul>
        </li>
    </ul>

@stop