@extends('layouts.default')

@section('title')«{!! $query !!}»@stop

@section('subtitle')@stop

@section('content')

    <section class="text-center">
        <h1 class="mt-5">@yield('title')</h1>
        <h2 class="mb-3">@yield('subtitle')</h2>
    </section>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($titles as $key => $title)
            <li class="nav-item">
                <a class="nav-link @if(array_key_first($titles) === $key) active @endif" id="{{$key}}-tab"
                   data-toggle="tab" href="#{{$key}}" role="tab" aria-controls="{{$key}}"
                   aria-selected="@if(array_key_first($titles) === $key) true @else false @endif">
                    {{$title['name']}}
                    <span class="small text-secondary">({{$title['count']}})</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="myTabContent">

        @if(count($persons))
            <div class="tab-pane fade @if(array_key_first($titles) === 'persons') show active @endif" id="persons"
                 role="tabpanel" aria-labelledby="persons-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $persons, 'persons', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($books))
            <div class="tab-pane fade @if(array_key_first($titles) === 'books') show active @endif" id="books"
                 role="tabpanel" aria-labelledby="books-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $books, 'books', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($films))
            <div class="tab-pane fade @if(array_key_first($titles) === 'films') show active @endif" id="films"
                 role="tabpanel" aria-labelledby="films-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $films, 'films', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($games))
            <div class="tab-pane fade @if(array_key_first($titles) === 'games') show active @endif" id="games"
                 role="tabpanel" aria-labelledby="games-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $games, 'games', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($albums))
            <div class="tab-pane fade @if(array_key_first($titles) === 'albums') show active @endif" id="albums"
                 role="tabpanel" aria-labelledby="albums-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $albums, 'albums', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($bands))
            <div class="tab-pane fade @if(array_key_first($titles) === 'bands') show active @endif" id="bands"
                 role="tabpanel" aria-labelledby="bands-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $bands, 'bands', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($companies))
            <div class="tab-pane fade @if(array_key_first($titles) === 'companies') show active @endif" id="companies"
                 role="tabpanel" aria-labelledby="companies-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $companies, 'companies', $options) !!}
                    </div>
                </div>
            </div>
        @endif

        @if(count($genres))
            <div class="tab-pane fade @if(array_key_first($titles) === 'genres') show active @endif" id="genres"
                 role="tabpanel" aria-labelledby="genres-tab">
                <div class="row">
                    <div class="col-md-12">
                        {!! ElementsHelper::getSection($request, $genres, 'genres', $options) !!}
                    </div>
                </div>
            </div>
        @endif

    </div>

@stop